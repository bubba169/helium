# Helium CMS

Helium is a CMS built on the idea of configs and handlers. It is designed to sit around a database structure of your choosing, providing sensible defaults with the minimal configuration and allowing overriding of the majority of its core behaviour.

Helium sits as a layer above your website allowing you to manage data in an unintrusive way. It does not interfere in any way with the front end of your website, nor does it doctate how you should structure or fetch your content. Helium is intended for developers who know what they want and need something that fits their methods instead of being fenced in by opinionated software. 

This does, however, mean Helium is not for everyone. It doesn't provide by default many of the comforts of a more integrated CMS such as routing or storage management. By leaving these to the developer, Helium focuses on being a tool to view and update a system's data rather than being a framework to manage the user experience.

Helium is built on Laravel and most of the methods used to configure and work with helium will be familiar to a Laravel user.

## Key Concepts

### Configs

Configs are configuration files that define how the CMS interacts with your data. Most of the configuration will be done through entites. Helium works on the basis of sensible defaults so some config options can be guessed based on minimal data. If you don't like Helium's defaults you always have the option to define any config value yourself.

### Entities

Entities are the config surrounding a model in the CMS. They describe to Helium how each model can be viewed and updated.

### Entries

Entries are an instance of a model.

### Listings

A listing is the way most people will navigate through entries for an entity. Lists can have filters, are searchable and can be sorted. Columns are configurable and each row in a list can have a set of actions.

### Forms

Forms are where you can interact with a single entry. They are most often a collection of fields, sometimes split into tabs, and a request type for handling those fields on submit.

### Fields

Fields are the interactive aspect of a form. As well as defining the visual aspect, they define multiple handlers for how to handle your data and how each field relates to the entity model.




