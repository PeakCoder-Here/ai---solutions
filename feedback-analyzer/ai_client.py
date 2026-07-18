"""
AI client wrapper for sentiment analysis and theme extraction.

Uses Google Gemini (gemini-1.5-flash) when a GEMINI_API_KEY environment
variable is set. Falls back to a keyword-based mock analyzer whenever:
  - no API key is configured (so the whole pipeline runs with zero setup), or
  - a live API call fails or returns output that can't be parsed.

This means analyzer.py never needs to know whether it's talking to the real
API or the mock - analyze_review() always returns a usable result.
"""

import json
import os
import re

SENTIMENTS = {"positive", "negative", "neutral"}

GEMINI_MODEL_NAME = "gemini-1.5-flash"

_gemini_model = None  # lazily created and cached across calls


def api_key_configured():
    """Whether a GEMINI_API_KEY is present in the environment."""
    return bool(os.environ.get("GEMINI_API_KEY"))


def _get_gemini_model():
    """Returns a configured Gemini model instance, or None if unavailable."""
    global _gemini_model
    if _gemini_model is not None:
        return _gemini_model

    if not api_key_configured():
        return None

    try:
        import google.generativeai as genai
    except ImportError:
        print("google-generativeai is not installed - falling back to mock analyzer.")
        return None

    genai.configure(api_key=os.environ["GEMINI_API_KEY"])
    _gemini_model = genai.GenerativeModel(GEMINI_MODEL_NAME)
    return _gemini_model


PROMPT_TEMPLATE = """Analyze the sentiment of this customer review and identify its main theme.

Review: "{review}"

Respond with ONLY a JSON object in this exact format, no other text:
{{"sentiment": "positive|negative|neutral", "theme": "3-5 word theme"}}
"""


def _call_gemini(model, review_text):
    """Sends one review to Gemini and parses the JSON response. Raises on any failure."""
    prompt = PROMPT_TEMPLATE.format(review=review_text)
    response = model.generate_content(prompt)
    raw_text = response.text.strip()

    # Gemini sometimes wraps JSON in markdown code fences - strip them if present.
    raw_text = re.sub(r"^```(json)?|```$", "", raw_text, flags=re.MULTILINE).strip()

    data = json.loads(raw_text)
    sentiment = str(data["sentiment"]).strip().lower()
    theme = str(data["theme"]).strip()

    if sentiment not in SENTIMENTS or not theme:
        raise ValueError(f"unexpected Gemini output: {data!r}")

    return sentiment, theme


# --- Mock fallback: a small keyword-based classifier ------------------------
# This is what lets the whole pipeline run with zero setup and no API key.

POSITIVE_WORDS = {
    "love", "great", "excellent", "amazing", "fast", "friendly", "helpful",
    "recommend", "perfect", "awesome", "fantastic", "easy", "best", "happy",
    "smooth", "quick", "impressed", "outstanding", "exceeded", "premium",
}

NEGATIVE_WORDS = {
    "bad", "terrible", "slow", "broken", "rude", "awful", "horrible", "worst",
    "disappointed", "poor", "late", "refund", "complain", "waste", "frustrat",
    "damaged", "crash", "freeze", "confusing", "overpriced", "never", "wrong",
}

# Each rule: keywords to look for, and the theme to report per sentiment.
THEME_RULES = [
    (("ship", "deliver", "arrived", "package"),
     "fast delivery", "slow delivery", "shipping experience"),
    (("quality", "material", "durable", "broke", "build"),
     "great build quality", "poor build quality", "average build quality"),
    (("price", "expensive", "cheap", "cost", "value", "money", "overpriced", "charged"),
     "good value for money", "poor value for money", "pricing"),
    (("support", "service", "staff", "rep", "help"),
     "great customer service", "poor customer service", "customer service"),
    (("app", "website", "checkout", "interface", "confusing"),
     "easy to use", "confusing interface", "usability"),
    (("packaging", "box", "wrapped"),
     "nice packaging", "damaged packaging", "packaging"),
]

DEFAULT_THEMES = {
    "positive": "positive overall experience",
    "negative": "negative overall experience",
    "neutral": "general feedback",
}


def mock_analyze(review_text):
    """Keyword-based sentiment + theme classifier. Requires no API key."""
    text = review_text.lower()

    pos_hits = sum(1 for word in POSITIVE_WORDS if word in text)
    neg_hits = sum(1 for word in NEGATIVE_WORDS if word in text)

    if pos_hits > neg_hits:
        sentiment = "positive"
    elif neg_hits > pos_hits:
        sentiment = "negative"
    else:
        sentiment = "neutral"

    for keywords, pos_theme, neg_theme, neutral_theme in THEME_RULES:
        if any(keyword in text for keyword in keywords):
            theme = {"positive": pos_theme, "negative": neg_theme, "neutral": neutral_theme}[sentiment]
            return sentiment, theme

    return sentiment, DEFAULT_THEMES[sentiment]


# --- Public entry point ------------------------------------------------------

def analyze_review(review_text):
    """
    Returns (sentiment, theme) for a single review.

    Tries the live Gemini API first if GEMINI_API_KEY is set; falls back to
    the keyword-based mock analyzer if no key is set, or if the live call
    fails or returns something unparseable. A single bad review is never
    allowed to crash the batch.
    """
    model = _get_gemini_model()
    if model is not None:
        try:
            return _call_gemini(model, review_text)
        except Exception as exc:
            print(f"  Gemini call failed ({exc}) - using mock analyzer for this review.")

    return mock_analyze(review_text)
