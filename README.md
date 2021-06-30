# CORS Proxy + Download Stream

## _The simplest and and efficient way to bypass CORS_

[![oFahel](https://i.imgur.com/hSyuS32.png)](https://github.com/ofahel/)

This project is the simplest way to proxy CORS.

Make simple things to do complex task

As [Steve Jobs][jobs_wiki] says on the [BusinessWeek, May 25 1998][jobs_quote]

> That’s been one of my mantras — focus and simplicity.
> Simple can be harder than complex:
> You have to work hard to get your thinking clean to make it simple.
> But it’s worth it in the end because once you get there, you can move mountains.

## ⚡ Features

- Proxy GET request.
- Stream Download content.
- Stream Content Type.
- Proxy third party services.
- Proxy JSON HTTP over HTTPS.
- Bypass Mixed Content, load HTTP resources by Proxy.
- Support Partial and Resumable downloads.
- Protect your connection and that of your server with the proxy support.
- Fake/Stream UserAgent

## 🎉 Installation/Usage

Download the cors.php and put on your project folder.

It's a build-in solution.

```php
<?php
include("cors.php");

//No more codes needed
?>
```

## 📄 Usage Example

| Parameter | Description                            | Rule       | Default |
|-----------|----------------------------------------|------------|---------|
| url       | Give the remove url to ?url= parameter | **Needed** |         |

## ✨ Demo
> Proxy page: https://cors-proxy.tk/?url=http://google.com/

> Proxy HTTP resource: https://cors-proxy.tk/?url=https://i.imgur.com/hSyuS32.png

## Development

Want to contribute? Great!
You are welcome 🥳

## License

GNU General Public License

[//]: # "These are reference links used in the body of this note and get stripped out when the markdown processor does its job. Thanks SO - http://stackoverflow.com/questions/4823468/store-comments-in-markdown-syntax"
[jobs_wiki]: https://en.wikipedia.org/wiki/Steve_Jobs
[jobs_quote]: https://www.bloomberg.com/news/articles/1998-05-25/steve-jobs-theres-sanity-returning
