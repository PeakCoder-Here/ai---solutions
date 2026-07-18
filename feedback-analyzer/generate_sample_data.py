"""
Generates a messy sample_reviews.csv for testing the feedback analyzer pipeline.

Run standalone with no arguments:
    python3 generate_sample_data.py

The output deliberately includes the kind of mess a real CSV export tends to
have - extra whitespace, a handful of exact duplicate rows, and a couple of
blank reviews - so analyzer.py has something realistic to clean up.
"""

import csv
import random

OUTPUT_FILE = "sample_reviews.csv"

CUSTOMER_NAMES = [
    "Maria Gonzalez", "James Chen", "Aisha Patel", "Liam O'Brien", "Sofia Rossi",
    "Noah Kim", "Emma Johnson", "Lucas Silva", "Olivia Martin", "Ethan Wright",
    "Ava Thompson", "Mateo Garcia", "Chloe Dubois", "Ryan Murphy", "Zara Ahmed",
    "Benjamin Lee", "Isabella Rodriguez", "Daniel Novak", "Grace Nguyen", "Samuel Osei",
    "Hannah Wilson", "Leo Fischer", "Mia Andersson", "Jack Sullivan", "Priya Sharma",
    "Oliver Bennett", "Layla Hassan", "Henry Clarke", "Amara Okafor", "Felix Bauer",
]

POSITIVE_REVIEWS = [
    "Absolutely love this product! Delivery was fast and the quality exceeded my expectations.",
    "Great customer service, they resolved my issue within minutes. Highly recommend!",
    "This is exactly what I needed. Easy to set up and works perfectly every time.",
    "Fantastic experience from start to finish. Will definitely be ordering again.",
    "The packaging was excellent and the item arrived in perfect condition. Very happy!",
    "Support team was incredibly helpful and friendly. Five stars all around.",
    "Best purchase I've made this year. The build quality is outstanding.",
    "Super fast shipping and the product works exactly as described. Impressed!",
    "I was skeptical at first but this exceeded all my expectations. Love it.",
    "Smooth checkout process and the app is really easy to use. Great job!",
    "The quality is amazing for the price. Would recommend to anyone.",
    "Customer support answered all my questions quickly and professionally.",
    "Ordered on Monday and it arrived by Wednesday. Couldn't be happier with the speed!",
    "The team went above and beyond to make sure I was satisfied. Truly great service.",
    "Everything about this purchase felt premium, from the packaging to the product itself.",
]

NEGATIVE_REVIEWS = [
    "Terrible experience. My order arrived three weeks late and no one responded to my emails.",
    "The product broke after two days of use. Very disappointed with the quality.",
    "Customer service was rude and unhelpful when I tried to get a refund.",
    "Way too expensive for what you actually get. Feels like a waste of money.",
    "The website is confusing and the checkout process kept crashing on me.",
    "Packaging was damaged and the item inside was broken. Requesting a refund.",
    "I've been waiting two weeks for a response about my missing order. Awful.",
    "The app constantly freezes and support never got back to me. Frustrating.",
    "Poor build quality, it stopped working within a week. Would not recommend.",
    "Shipping took forever and when it arrived, the wrong item was in the box.",
    "Worst customer service I've dealt with. They never resolved my complaint.",
    "Overpriced and underwhelming. Not worth the money at all.",
    "Received a damaged item and it took forever to get a replacement sorted out.",
    "The instructions were unclear and support was no help when I asked questions.",
    "Charged me twice for the same order and it took a week to get it fixed.",
]

NEUTRAL_REVIEWS = [
    "The product is okay, does what it says but nothing special.",
    "Delivery took about a week, which seems standard I guess.",
    "It's fine for the price. Not amazing, not terrible.",
    "I received the item as described. No complaints but nothing stood out either.",
    "Average experience overall. The product works as expected.",
    "Customer service responded within a day, which was reasonable.",
    "The packaging was standard, nothing fancy but did the job.",
    "It works fine, though I probably wouldn't buy it again.",
    "Decent quality for the price point. Meets basic expectations.",
    "The checkout process was straightforward, nothing memorable either way.",
    "Nothing wrong with it, just didn't blow me away either.",
    "Took a bit longer than expected but eventually arrived in good shape.",
]


def build_rows():
    """Assembles the full list of row dicts, including the deliberate mess."""
    random.seed(42)  # reproducible output across runs

    name_pool = CUSTOMER_NAMES.copy()
    random.shuffle(name_pool)

    all_reviews = POSITIVE_REVIEWS + NEGATIVE_REVIEWS + NEUTRAL_REVIEWS
    random.shuffle(all_reviews)

    rows = []
    review_id = 1
    for i, text in enumerate(all_reviews):
        name = name_pool[i % len(name_pool)]

        # Sprinkle in extra whitespace to mimic a messy real-world export.
        if i % 4 == 0:
            text = f"   {text}  "
        if i % 7 == 0:
            name = f"  {name} "

        rows.append({"review_id": review_id, "customer_name": name, "review_text": text})
        review_id += 1

    # A couple of blank/empty reviews.
    rows.append({"review_id": review_id, "customer_name": "Tom Bradley", "review_text": ""})
    review_id += 1
    rows.append({"review_id": review_id, "customer_name": "Nina Petrov", "review_text": "   "})
    review_id += 1

    # A few exact duplicate rows, as if the same entry got exported twice.
    for dup_index in (2, 10, 20, 30):
        rows.append(dict(rows[dup_index]))

    return rows


def main():
    rows = build_rows()

    with open(OUTPUT_FILE, "w", newline="", encoding="utf-8") as f:
        writer = csv.DictWriter(f, fieldnames=["review_id", "customer_name", "review_text"])
        writer.writeheader()
        writer.writerows(rows)

    print(f"Generated {len(rows)} sample reviews -> {OUTPUT_FILE}")


if __name__ == "__main__":
    main()
