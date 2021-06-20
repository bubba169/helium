# Helium CMS

Helium is a CMS built on the idea of configs and handlers. It is designed to sit around a database structure of your choosing, providing sensible defaults with the minimal configuration and allowing overriding of the majority of its core behaviour.

Helium sits as a layer above your website allowing you to manage data in an unintrusive way. It does not interfere in any way with the front end of your website, nor does it doctate how you should structure or fetch your content.

Helium is intended for developers who know what they want and need something that fits their methods instead of being fenced in by opinionated software.

## Key Concepts

### Configs

Configs are dynamic configuration files that define how the CMS displays your data. Most of the configuration will be done through entites. Helium works on the basis of sensible defaults so some config options can be guessed based on minimal data. If you don't like Helium's defaults you always have the option to define any config value yourself.

### Entities

Entities are the config equivalent to models in the CMS. They describe to Helium how a model should be handled. Usually an entity will have a list and one or more forms. 

### Entries

Entries are an instance of a model. In most cases they will be a row in a table.

### Lists

A list is the way most people will navigate through entries for an entity. Lists can have filters, are searchable and can be sorted. Columns are configurable and each row in a list can have a set of actions.

### Forms

Forms are screens in the CMS where you interact with a single entry. Fields can be defined on the entity level to be shared between forms or you can add new fields per form.

### Fields

Fields can be added to forms and usually represent an input for a model attribute.
