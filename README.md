# Portfolio Web System

### Introduction

The goal of this project is to create an open-source portfolio web system that anyone can use to develop a personal online presence through blogging and portfolio building.

### Authoring Blog Posts

Individual blog posts are stored in XML format in the `/blogs/` directory. To add new blog posts to the system simply store your new posts in the following XML format and place them in the `/blogs/` directory. The web system will detect all blog posts stored in this directory and render in order from the most recent post to the oldest post.

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

Additional optional tags that are not currently being used, but for which we have plans to implement uses, include `<excerpt>` and `<tag>`, with planned support for multiple `<tag>` entries in the same blog post.  

The `<content>` tag currently supports a subset Markdown symbols. Complete support of all Markdown syntax is planned for the future. The following Markdown elements are currently supported:

- Heading - #, ##, ###, etc.
- Bold - \*\*bold text\*\*
- Italic - \*italicized text\*
- Bold & Italic - \*\*\*bold and italicized text\*\*\*
- Link - \[title\]\(https://www.example.com\)
- Image - !\[alt text\]\(image.jpg\)

### Stack Example

An example deployment of the portfolio web system can be found at [www.christianwestbrook.dev](https://www.christianwestbrook.dev). In this section we briefly describe the technology stack supporting site deployment at christianwestbrook.dev to demonstrate what a Portfolio Web System deployment could look like.  

- The domain name [www.christianwestbrook.dev](https://www.christianwestbrook.dev) is registered at [Google Domains](https://domains.google/)
- DNS service connecting the domain name to the origin network is provided by [CloudFlare](https://www.cloudflare.com/)
- Requests transmitted to the origin network are routed to the origin server through configured port forwarding
- The origin server is a [Raspberry Pi](https://www.raspberrypi.com/) computer
- An [Apache HTTP Server](https://httpd.apache.org/) deployed to the origin server processes web requests
- An SSL certificate enabling the servicing of HTTPS requests is provided by [CloudFlare](https://www.cloudflare.com/)
- A deployment of the [Portfolio Web System](https://github.com/christian-westbrook/portfolio-web-system) is installed on the HTTP server

### Release Example

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