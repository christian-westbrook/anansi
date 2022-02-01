# Portfolio Web System

## Table of Contents
- [Introduction](#introduction)
- [Table of Contents](#table-of-contents)
- [Getting Started](#getting-started)
- [Configuration](#configuration)
- [Authoring Blog Posts](#authoring-blog-posts)
- [Stack Example](#stack-example)
- [Release Example](#release-example)

## Introduction

The [portfolio web system](https://www.github.com/christian-westbrook/portfolio-web-system/) is an open-source solution that anyone can use to develop a personal online presence through blogging and portfolio building.  

The current release provides a simple blog engine that can quickly be configured, loaded with content, and deployed. Blog posts are written in a predefined XML and Markdown format.  

Future releases will include support for interactions, an integrated project portfolio, and continued enhancements to the blog authoring process.  

## Getting Started

- Clone the [repository](https://github.com/christian-westbrook/portfolio-web-system.git) to your machine
- Modify the `/public/config.json` file to customize the system to meet your needs
	- Learn more in the [configuration](#configuration) section
- Use `/public/blogs/demo.xml` as an example to start writing blogs
	- Learn more in the [authoring blog posts](#authoring-blog-posts) section
- Regularly back up `/blogs/`, `/img/`, and `config.json`
- Move the contents of the `/public/` folder into a web server's deployment directory
	- Must have [PHP](https://www.php.net/) installed on the machine hosting the system
	- Must use a web server that supports PHP, such as [Apache HTTP Server](https://httpd.apache.org/)
	- PHP needs to be [enabled](https://stackoverflow.com/questions/42654694/enable-php-apache2) if using Apache
- Copy your `/blogs/`, `/img/`, and `config.json` into the deployment directory
- Use a browser to navigate to the deployed system

## Configuration

You can customize a deployment of the Portfolio Web System through the use of system configuration settings located in the file `/public/config.json`. Each entry within this JSON file represents a particular configuration setting.

The following configuration settings are currently supported by the portfolio web system:  
- **domain** - The domain name of your website  
- **title** - The text rendered in the heading  

As an example, the following `config.json` file is used at [christianwestbrook.dev](https://www.christianwestbrook.dev/).  

`{`  
`"domain" : "https://www.christianwestbrook.dev",`  
`"title"  : "christianwestbrook.dev"`  
`}`  

More configuration settings are planned for future releases. To request a particular customization, feel free to submit an issue [here](https://github.com/christian-westbrook/portfolio-web-system/issues) using the `enhancement` label.

## Authoring Blog Posts

Individual blog posts are stored in XML format in the `/blogs/` directory. To add a new blog post to the system, create a new blog file using the following XML format and place it in the `/blogs/` directory. The system will detect all blog posts stored in this directory and render them in order from the most recent post to the oldest post.

The following demonstrates the minimum requirements for a single blog post.

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

Additional optional tags that are not currently being used, but for which we have plans to implement, include `<excerpt>` and `<tag>`, with planned support for multiple `<tag>` entries in the same blog post.  

The `<content>` tag currently supports a subset Markdown symbols. Complete support of all Markdown syntax is planned for the future. The following Markdown elements are currently supported:

- Heading - #, ##, ###, etc.
- Bold - \*\*bold text\*\*
- Italic - \*italicized text\*
- Bold & Italic - \*\*\*bold and italicized text\*\*\*
- Link - \[title\]\(https://www.example.com\)
- Image - !\[alt text\]\(image.jpg\)

More blog authoring features are planned for future releases. To request a particular feature, feel free to submit an issue [here](https://github.com/christian-westbrook/portfolio-web-system/issues) using the `enhancement` label.

## Stack Example

An example deployment of the portfolio web system can be found at [www.christianwestbrook.dev](https://www.christianwestbrook.dev). In this section we briefly describe the technology stack supporting site deployment at christianwestbrook.dev to demonstrate what a Portfolio Web System deployment could look like.  

- The domain name [www.christianwestbrook.dev](https://www.christianwestbrook.dev) is registered at [Google Domains](https://domains.google/)
- DNS service connecting the domain name to the origin network is provided by [CloudFlare](https://www.cloudflare.com/)
- Requests transmitted to the origin network are routed to the origin server through configured port forwarding
- The origin server is a [Raspberry Pi](https://www.raspberrypi.com/) computer
- An [Apache HTTP Server](https://httpd.apache.org/) deployed to the origin server processes web requests
- An SSL certificate enabling the servicing of HTTPS requests is provided by [CloudFlare](https://www.cloudflare.com/)
- A deployment of the [Portfolio Web System](https://github.com/christian-westbrook/portfolio-web-system) is installed on the HTTP server

## Release Example

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

## Author

Christian Westbrook