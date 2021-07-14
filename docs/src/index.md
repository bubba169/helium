# Helium CMS

## What is it?
As a member of a web development agency I fully appreciate that when it comes to bespoke websites, there is no one size fits all approach. Client's needs differ and diverge and maintaining a single CMS setup is futile; you'll end up pushing square pegs into round holes trying to make code reusable.

Enter Helium. Helium is a configurable CMS toolkit. It is designed to sit around a database structure of your choosing, providing a user interface with sensible defaults from minimal configuration. It is built on the idea of configuration and handlers, being modular and extendable, easily allowing overriding the majority of its core behaviour.

## Why Helium?
Unlike other systems which manage your data and weave it into their complicated database formats, Helium sits as a layer aside from your website allowing you to manage data in an unintrusive way. It does not interfere in any way with the presentation of your website, nor does it dictate how you should structure or fetch your content. 

Helium is intended for developers who know what they want and need something that allows them to manage their data in a way they define instead of being fenced in by opinionated software. Helium is built on Laravel and most of the methods used to configure and work with helium will be familiar to a Laravel user.

## There's a catch.
Helium is not for everyone. It's not another Wordpress. It doesn't provide by default many of the comforts of a more integrated CMS such as routing or asset management. By leaving these to the developer, Helium focuses on being a tool to view and update a system's data rather than being a framework to manage the entire user experience.

## Key Concepts

- **Configs** define how the CMS presents and interacts with your data. Helium works on the basis of sensible defaults. Many config options can be deduced based on minimal data. If you don't like Helium's defaults, you always have the option to define any config value yourself.

- **Entities** are the config representing a model in the CMS. They define a collection of views centered around a Laravel model.

- **Entries** are an instance of an Entity.

- **Views** represent pages in the CMS and act as a window into your data. They can take the form of tables, forms or something of your own design. Helium provides built in support for configurable tables and forms.

- **Tables** provide a listing of entries with configurable actions and filters.

- **Forms** are a collection of fields that can be used to create and update entries.

- **Fields** are the interactive aspect of a form. As well as defining the visual aspect, they define multiple handlers for managing your data and how each field relates to the entity model.

- **Templates,** built using twig, are the presentation layer of the CMS. They define how each element appears visually and define the markup rendered by the browser.



