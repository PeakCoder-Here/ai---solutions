# AI-Powered Customer Feedback Analyzer

A command-line tool that turns a messy customer reviews CSV into a polished,
client-ready Excel report using AI-driven sentiment analysis.

## What it does

1. Cleans a raw reviews CSV — strips whitespace, drops duplicate rows and
   blank reviews, and reports how many usable reviews remain.
2. Runs every review through Google Gemini (`gemini-1.5-flash`) to classify
   sentiment (positive / negative / neutral) and extract a short theme
   (e.g. "slow delivery", "great customer service").
3. Generates a colored sentiment breakdown bar chart (PNG).
4. Builds a two-sheet Excel report, `feedback_report.xlsx`:
   - **Summary** — total reviews, sentiment breakdown, top 5 themes by
     frequency, and the embedded chart.
   - **All Reviews** — the full cleaned dataset with a styled header row.

## Runs out of the box — no API key required

If `GEMINI_API_KEY` isn't set, the tool automatically switches to a built-in
keyword-based mock analyzer, so the entire pipeline runs and produces a real
report with zero setup. Any individual review that fails against the live
API (network error, unparseable response, etc.) also falls back to the mock
analyzer for that row, so one bad review never crashes the batch.

## Setup

```bash
pip install -r requirements.txt
```

That's it — the tool works immediately in mock mode.

### (Optional) Use the real Gemini API

1. Go to [Google AI Studio](https://aistudio.google.com/app/apikey) and sign
   in with a Google account.
2. Click **Create API key** (it's free) and copy the key.
3. Set it as an environment variable before running the tool:

   ```bash
   # macOS/Linux
   export GEMINI_API_KEY="your-key-here"

   # Windows (PowerShell)
   $env:GEMINI_API_KEY = "your-key-here"
   ```

Leave the variable unset to keep using mock mode.

## Usage

Generate a fresh sample dataset (optional — `sample_reviews.csv` is already
included):

```bash
python3 generate_sample_data.py
```

Run the analyzer:

```bash
python3 analyzer.py sample_reviews.csv
```

Or on any of your own CSVs, as long as they have `review_id`,
`customer_name`, and `review_text` columns:

```bash
python3 analyzer.py path/to/your_reviews.csv
```

Omitting the argument defaults to `sample_reviews.csv`:

```bash
python3 analyzer.py
```

Output:
- `feedback_report.xlsx` — the two-sheet Excel report
- `sentiment_breakdown.png` — the standalone chart (also embedded in the report)

## Project structure

```
feedback-analyzer/
├── generate_sample_data.py   # Creates a messy sample_reviews.csv
├── ai_client.py               # Gemini wrapper + keyword-based mock fallback
├── analyzer.py                 # Clean -> analyze -> chart -> Excel report
├── requirements.txt
└── README.md
```

## Tech stack

- **pandas** — CSV loading and cleaning
- **google-generativeai** — Gemini API client for sentiment/theme extraction
- **matplotlib** — sentiment breakdown chart
- **openpyxl** — styled, multi-sheet Excel report generation
