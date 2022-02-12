# Anansi 🕷️

An open-source blogging and portfolio engine that anyone can use to own their story.

## Table of Contents
1. [Introduction](#introduction)
2. [Features](#features)
3. [Access](#access)
4. [Configure](#configure)
5. [Create](#create)
6. [Deploy](#deploy)
7. [Help](#help)

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
- Portfolio engine
- Blog post pages
- Blog post interactions
- JavaScript front-end
- Complete parsing of Markdown in blog content

## Getting Started

The following sections provides instructions to quickly get started. If you get stuck at any point, feel free to reach out for help. The best way to do so is to [create an issue](https://github.com/christian-westbrook/anansi/issues).

### Prerequisites
- You have [PHP](https://www.php.net/) installed on your production machine
- You have web server software installed and configured to use PHP on your production machine
	- [Apache HTTP Server](https://httpd.apache.org/) is free and supports PHP
	- PHP needs to be [enabled](https://stackoverflow.com/questions/42654694/enable-php-apache2) if using Apache

### Create
- Use `/public/blogs/demo.xml` as an example to start writing blogs
- Place completed blogs into the `/public/blogs/` folder and referenced images into the `/public/img/` folder
- Regularly back up `/public/blogs/` and `/public/img/`
	- Learn more in the [authoring blog posts](#authoring-blog-posts) section

### Deploy
- Move the contents of the `/public/` folder into your web server's deployment directory
- If kept separate, copy your `/blogs/`, `/img/`, and `config.json` into the deployment directory
- Navigate to your deployed system using a web browser to confirm success
	- Learn more in the [deployment](#deploy) section

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

Blog posts are defined in an XML format and stored in the `/public/blogs/` directory. To add a new blog post to the system, create a new blog file using the following XML format and place it in the `/public/blogs/` directory. The blog engine will detect and render all blog files stored in this directory.

To quickly get started with creating blogs, you can use the file `/public/blogs/demo.xml` as an example.

To preview what your blog feed will look like deployed, open a terminal or command prompt and navigate to the `/public/` directory. If you have PHP installed, you can start a local server that will host your copy of the Anansi platform by using a command like `php -S localhost:8000` from inside the `/public/` directory. You can then navigate to the preview using a web browser, which in this example would be located at `http://localhost:8000` 

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

We want to help you create inspiring content. More blog authoring features are planned for future releases. To request a particular feature, feel free to submit an issue [here](https://github.com/christian-westbrook/anansi/issues) using the `enhancement` label.

## Deploy

In this section we briefly describe the release process supporting site deployment at christianwestbrook.dev to demonstrate what a Portfolio Web System release process could look like.

- Ensure that the origin Raspberry Pi server is online
- Connect to the origin server
- Ensure that the Apache HTTP Server is online with the terminal command `service apache2 start`
- Clone the repository onto the origin server
- Check out and pull the target release branch
- Create a backup of the existing deployment by zipping all contents of the Apache HTTP Server deployment directory at `//var/www/html/`
- Copy all contents of the `/public/` directory within the repository to the Apache HTTP Server deployment directory at `//var/www/html/`
- Create an `/img/` directory within the Apache HTTP Server deployment directory at `//var/www/html/`
- Move desired blog data into `//var/www/html/blogs/`
- Move desired image data into `//var/www/html/img/`
- Navigate to [www.christianwestbrook.dev](https://www.christianwestbrook.dev) to confirm that the newly released web system is available

## Authors

[Christian Westbrook](https://www.christianwestbrook.dev)