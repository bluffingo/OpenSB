# squareBracket API v1

*Warning: The squareBracket API is currently in development and is therefor not stable.*

The squareBracket API contains various entrypoints to fetch data intended to be used for applications interfacing with squareBracket. It is currently not intended to be used to edit data, and as such requires no authentication to use it.

Input arguments are received as GET arguments (`foo.php?bar=baz` where `bar` is an argument for the `foo.php` entrypoint), and output data is sent as JSON encoded data. Input arguments are optional unless stated otherwise.

## /api/v1/video.php
This entrypoint fetches all relevant data for the specified video.

### Input arguments
| field | type    | description                  |
| ----- | ------- | ---------------------------- |
| id    | integer | Public Video ID *(required)* |

### Output data
| field       | type              | description                                                     |
| ----------- | ----------------- | --------------------------------------------------------------- |
| id          | string            | Public Video ID                                                 |
| title       | string            | Video Title                                                     |
| description | string            | Full video description                                          |
| time        | integer           | Unix timestamp of the time the video was uploaded               |
| views       | integer           | Amount of video views                                           |
| videofile   | string            | Path to the video's MPD file relative to the squareBracket root |
| videolength | integer           | Length of the video in seconds                                  |
| flags       | videoflags object | Video flags                                                     |
| tags        | string            | Comma-separated list of tags                                    |
| author      | user object       | Author of the video                                             |

### Example output
```json
{
	"id": "ABCdefGHI__",
	"title": "Example Video",
	"description": "This video is exactly two minutes long",
	"time": 1625162716,
	"views": 35,
	"videofile": "videos\/ABCdefGHI__.mpd",
	"videolength": 120,
	"flags": {
		"processing": 0,
		"featured": 1
	},
	"tags": "",
	"author": {
		"username": "ROllerozxa"
	}
}
```
