# Helium CMS

Helium is a CMS built on the idea of configuration and handlers. It is designed to sit around a database structure of your choosing, providing sensible defaults with the minimal configuration and allowing overriding of the majority of its core behaviour.

Helium sits as a layer aside from your website allowing you to manage data in an unintrusive way. It does not interfere in any way with the presentation of your website, nor does it dictate how you should structure or fetch your content. Helium is intended for developers who know what they want and need something that allows them to manage their data in a way they define instead of being fenced in by opinionated software. 

This does, however, mean Helium is not for everyone. It doesn't provide by default many of the comforts of a more integrated CMS such as routing or asset management. By leaving these to the developer, Helium focuses on being a tool to view and update a system's data rather than being a framework to manage the user experience.

Helium is built on Laravel and most of the methods used to configure and work with helium will be familiar to a Laravel user.

## Key Concepts

### Configs
Configs are configuration files that define how the CMS interacts with your data. Helium works on the basis of sensible defaults. Many config options can be deduced based on minimal data. If you don't like Helium's defaults you always have the option to define any config value yourself.

### Handlers
Handlers are the logic side of the CMS. Configurations will specify the handlers to be used in each situation.

### Entities
Entities are the config representing a model in the CMS. They describe to Helium how each model can be viewed and updated.

### Entries
Entries are an instance of an Entity.

### Views
A view is a page in the CMS and a window into your data. They can take the form of tables, forms or something of your own design. Helium provides built in support for configurable tables and forms.

#### Tables
Tables provide a listing of entries with configurable actions.

#### Forms
Forms are a collection of fields that can be used to create and update entries.

#### Fields
Fields are the interactive aspect of a form. As well as defining the visual aspect, they define multiple handlers for how to handle your data and how each field relates to the entity model.

### Templates
Built using twig, templates are the presentation layer of the CMS. They define how each element appears visually and define the markup rendered by the browser.


