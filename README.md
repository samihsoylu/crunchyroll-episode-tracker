[![GitHub license](https://img.shields.io/github/license/samihsoylu/crunchyroll-syncer?style=for-the-badge)](https://github.com/samihsoylu/youtrack-api-php/blob/master/LICENSE)
![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/samihsoylu/crunchyroll-syncer?style=for-the-badge)
![GitHub release (latest by date)](https://img.shields.io/github/v/release/samihsoylu/crunchyroll-syncer?label=stable&style=for-the-badge)
![Packagist Downloads](https://img.shields.io/packagist/dt/samihsoylu/crunchyroll-syncer?style=for-the-badge)
![Codecov](https://img.shields.io/codecov/c/github/samihsoylu/crunchyroll-syncer?style=for-the-badge)

# Crunchyroll syncer
Crunchyroll syncer is a tool designed to solve the challenges of sharing a single Crunchyroll account among multiple users. This project leverages Crunchyroll's RSS feed to automate the tracking of new episodes and integrates it with your Notion series page. With this tool, you'll no longer need to sift through cluttered watchlists or worry about missing the latest episodes of your favourite series.

## How it Works
When a new episode of a series you're following is released, this tool automatically updates the corresponding entry in your Notion Series page. Here's what happens behind the scenes:

1. **Series Tracking**: You manually add the anime series you are watching to your Notion Series page.
2. **RSS Feed Monitoring**: Crunchyroll Syncer continually checks the Crunchyroll RSS feed for new episodes of the series you are tracking.
3. **Notion Page Updates**: When a new episode is released, the tool updates your Notion Series page. It marks the series with a "new episode" status and provides a direct watch URL. This allows you to immediately access the latest episode with just a click.
4. **Resetting Series Status**: After watching the new episode, you need to update the series status back to "watched" on your Notion Series page. This action is crucial as it signals Crunchyroll Syncer to resume checking the RSS feed for new episodes of that particular series.


## Feature highlights
* **Personalized Tracking**: Individualized records of your watched episodes and pending shows.
* **Automatic Updates**: Badge notifications for new episodes, directly linked to the episode URL.
* **Notion Integration**: Syncs with a Notion template provided, allowing you to manage your anime watching experience from one centralized location.

## Requirements
* PHP 8.2 or later.
* [Notion](https://www.notion.so/) account

### PHP Extensions
* [json](https://www.php.net/manual/en/book.json.php)
* [pcre](https://www.php.net/manual/en/book.pcre)
* [mbstring](https://www.php.net/manual/en/book.mbstring.php)
* [simplexml](https://www.php.net/manual/en/book.simplexml.php)

## Documentation
* [How it works](https://samihsoylu.notion.site/How-it-works-a4564e085479494dae53a89546458677)
* [Installation](https://samihsoylu.notion.site/Installation-becc6a2b08cc41ff86037c584e91e122)

