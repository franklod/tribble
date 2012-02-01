# A simple dribbble clone!

## Features:

* Post images with title, descriptio, and tags
* Image color palette extraction and display
* Comments on posts
* Reply to a post with another (rebound)
* List posts by:
** Most recent
** Most replies
** Most likes
* Simple text search
* Find posts by tag
* Find posts by user


### Installation requirements:

* php5
* php5-curl
* php5-memcache
* php5-gd
* php5-mysql
* mysql-server
* apache mod_rewrite (optional)

There are 2 sql scripts in the schema folder.
* tribble_schema = generates an empty database
* tribble_schema_test = generates a database populated with fake posts and users