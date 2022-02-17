# Anansi 🕷️

An open-source blogging and portfolio engine that anyone can use to own their story.

## Table of Contents
1. [Introduction](#introduction)
2. [Features](#features)
3. [Access](#access)
4. [Configure](#configure)
5. [Create](#create)
6. [Deploy](#deploy)
7. [Getting Help](#getting-help)
8. [Authors](#authors)

## Introduction

[Anansi](https://www.github.com/christian-westbrook/anansi/) is a web system designed to help creative professionals not only tell their stories, but own them.

Anansi provides a blog engine with a simple XML interface for creating blogs. By providing a platform that you can own, Anansi helps you to also own your content and your audience. Download the Anansi platform, quickly configure it to meet your needs, create inspiring content, and deploy.

## Features

### Implemented
- PHP blog engine
- XML blog definition
- Configurable header
- Partial parsing of Markdown in blog content

### Planned
- About me page
- Portfolio engine
- Blog post pages
- Blog post interactions
- JavaScript front-end
- Complete parsing of Markdown in blog content

## Access

You can acquire a copy of the Anansi platform by either cloning the [repository](https://github.com/christian-westbrook/anansi) or by downloading its latest [release](https://github.com/christian-westbrook/anansi/releases). You can then configure your copy to meet your needs, load your copy with blog content, and deploy your copy to a web server. 

## Configure

You can customize your deployment through the use of system configuration settings located in the file `/public/config.json`. Each entry within this JSON file represents a particular setting. To change a setting, modify and save its value in the `config.json` file. It's a good idea to regularly back up this file.

The following configuration settings are currently supported:  
- **domain** - The domain name of your site  
- **title** - Text to be rendered in the site header  

As an example, the following `config.json` file is deployed at [christianwestbrook.dev](https://www.christianwestbrook.dev/).  

`{`  
`"domain" : "https://www.christianwestbrook.dev",`  
`"title"  : "christianwestbrook.dev"`  
`}`  

More configuration settings are planned for future releases. To request a particular setting, feel free to submit an issue [here](https://github.com/christian-westbrook/anansi/issues) using the `enhancement` label.

## Create

Blog posts are defined in an XML format and stored in the `/public/blogs/` directory. To add a new blog post to your system, create a new blog file using the following XML format and place it in the `/public/blogs/` directory. The blog engine will detect and render all blog files stored in this directory.

To quickly get started with creating blogs, you can use the file `/public/blogs/demo.xml` as an example.

To preview what your blog feed will look like once it's deployed, open a terminal or command prompt session and navigate to the `/public/` directory. If you have PHP installed, you can start a local server that will host your copy of the Anansi platform by using a command like `php -S localhost:8000` from inside the `/public/` directory. You can then navigate to the preview using a web browser, which in this example would be located at `http://localhost:8000` 

The following block defines the minimum requirements for a single blog post.

```
<?xml version="1.0" encoding="UTF-8"?>
<blog>
	<title></title>
	<author></author>
	<abstract></abstract>
	<thumbnail></thumbnail>
	<content></content>
	<date></date>
	<time></time>
</blog>
```

Optional tags that are not currently supported but that have plans to be implemented include `<excerpt>` and `<tag>`, both of which are demonstrated in the file `/public/blogs/demo.xml`.

The `<content>` tag currently supports a subset Markdown symbols. Complete support of all Markdown syntax is planned for the future. The following Markdown elements are currently supported:

- Heading - #, ##, ###, etc.
- Newline - Two consecutive spaces trailing a line
- Bold - \*\*bold text\*\*
- Italic - \*italicized text\*
- Bold & Italic - \*\*\*bold and italicized text\*\*\*
- Link - \[title\]\(https://www.example.com\)
- Image - !\[alt text\]\(image.jpg\)

Local images should be stored in the `/public/img/` directory for convenience. An example of a reference to an image file from inside a blog file can be found in the file `/public/blogs/demo.xml`.  

Be sure to regularly back up both your `/public/blog/` and `/public/img/` directories.

We want to help you create inspiring content. More blog authoring features in future releases will help you do that. To request a particular feature, feel free to submit an issue [here](https://github.com/christian-westbrook/anansi/issues) using the `enhancement` label.

## Deploy

Before attempting to deploy, ensure that you have access to a [web server](https://httpd.apache.org/) configured to read [PHP](https://www.php.net/) files.

Releasing your copy of the Anansi platform is as simple as moving the contents of your `/public/` folder into your web server's public directory.  

Your blog files should be stored in the deployed `/blogs/` folder and your images in the deployed `/img/` folder. If you've kept your `config.json` file separate, then be sure to include it in your deployment as well.  

## Getting Help

If you have any issues getting started with Anansi, feel free to reach out to the authors for help. The best way to do this would be to submit an issue [here](https://github.com/christian-westbrook/anansi/issues) using the `help wanted` or `question` labels.

## Authors

[Christian Westbrook](https://www.christianwestbrook.dev)