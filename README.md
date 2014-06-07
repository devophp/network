# Devophp/Network

The goal of this project is to have a unified way of programming your large-scale dynamic networks. Think of it as a blend between puppet, nagios, collectd, graphite, shipyard, security groups, cloudformation.

## How does it work?

Devophp/Network allows you to define your network as a set of **resources** in a .yml file.

Example resource types:

* Nodes
* NetworkInterfaces
* Classes
* IpRuleSets
* ...etc

Each 'Resource' has a unique `id` and can have zero or more `classes`. The id and class structure allows you to target your resources in comparable way to CSS selectors.

Once you have defined your network (resources) in a .yml file, you can load the topology into memory. The loaded structure can now be used to develop various network-based configuration management tools.

Example tools:

- Distributed iptable rules generator: Define rules by referencing other nodes by id or classname (dynamic configuration)
- Configuration management: define installed packages, and generate config files per host
- Monitorring: define monitorring rules by classnames per host

Please refer to the `example/` directory for example network configurations.

This project is in early-stage development


## Installation

This project uses [composer](http://getcomposer.org) to manage it's dependencies. Simply run `composer install` to install all dependencies in the `vendor/` directory.

## Commands:

You can find the command-line utility in `bin/console`

### Command network:parse

`bin/console network:parse example/test.yml`. This will load the defined network into memory and output the structure to the console.

### Command network:iprules

`bin/console network:iprules example/test.yml`. This will load the defined network into memory and output an iptables .rules file. This tool allows you to reference other resources, similar to 'exported resources' in Puppet.



