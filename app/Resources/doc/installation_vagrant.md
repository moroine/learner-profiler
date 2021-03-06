#Vagrant Installation

##Prerequisites

To install via Vagrant you first need to install:

- [Vagrant](https://www.vagrantup.com/downloads)
- A box provider provider, we have tested with [VirtualBox](https://www.virtualbox.org/)

##Installation

To install the project simply run `vagrant up` command from project directory.

##Configuring LearnerProfiler

You have to edit app/config/parameters.yml to specify the missing parameters:

- gplace_api_key: ~ # Your [GPlace API](https://developers.google.com/places/documentation/) Key
- geonames_api_username: ~ # Your [Geoname](http://www.geonames.org/) username

##Using Symfony console

- Connect to the Box: `vagrant ssh`
- Go to project directory: `cd /vagrant`
- Access Symfony console: `php app/console`

##Accessing Website

The website is available in the following address: [localhost:8080](http://localhost:8080).

##Next Steps

- [Import Moodle Datas](import_moodle.md)
- [Access the Api](api_access.md)