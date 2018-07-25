# Task assignment
Web application for assignment of tasks developed in [Symfony 4](https://symfony.com/4).
* [Which is used in the project](https://github.com/sergio-santiago/task-assignment#which-is-used-in-the-project)
* [General functionality](https://github.com/sergio-santiago/task-assignment#general-functionality)
* [Installation](https://github.com/sergio-santiago/task-assignment#installation)
  * [What do you need to have installed previously?](https://github.com/sergio-santiago/task-assignment#what-do-you-need-to-have-installed-previously)
  * [Installation and start-up instructions](https://github.com/sergio-santiago/task-assignment#installation-and-start-up-instructions)
* [Screenshots of some views](https://github.com/sergio-santiago/task-assignment#screenshots-of-some-views)
## Which is used in the project
* [Composer](https://getcomposer.org/) to manage Symfony dependencies.
* [Bootstrap 3](https://getbootstrap.com/docs/3.3/) as front-end component library.
* [Font Awesome 4](https://fontawesome.com/v4.7.0/) as icons package.
* [Bootbox.js](http://bootboxjs.com/) to send alerts and confirmations with JS.
* [Elasticsearch](https://www.elastic.co/products/elasticsearch) as search engine of the application, to work needs [JAVA](https://www.java.com/en/download/help/download_options.xml).
* [Symfony translation](https://symfony.com/doc/current/translation.html) for translations, the application is translated into Spanish and English
* [Webpack Encore](https://symfony.com/doc/current/frontend.html) to manage CSS and JavaScript. To work needs [Node.js](https://nodejs.org/en/download/) and the package manager [YARN](https://yarnpkg.com/lang/en/docs/install/#debian-stable)
* Third-party bundles for some tasks such as paging ([KnpPaginatorBundle](https://github.com/KnpLabs/KnpPaginatorBundle)) or integrate more easily other technologies such as elasticsearch ([FOSElasticaBundle](https://github.com/FriendsOfSymfony/FOSElasticaBundle))
* The application makes use of the most common tools used in Symfony projects such as [forms](https://symfony.com/doc/current/forms.html), [validators](https://symfony.com/doc/current/validation.html), [console commands](http://symfony.com/doc/current/console.html), [security system](https://symfony.com/doc/current/security.html) ... etc
## General functionality
The application has 2 types of users, users with administrator role and users with user role:
* **Administrators** can manage system users and tasks. They are also responsible for assigning tasks to common users
* **Common users** can see the tasks assigned to them and mark them as completed
![general_functionality](https://image.ibb.co/eY9auT/general_functionality.png)
## Installation
### What do you need to have installed previously?
To install the application and everything works correctly you need to have installed the following
1. [Composer](https://getcomposer.org/)
2. [Node.js](https://nodejs.org/en/download/)
3. [YARN](https://yarnpkg.com/lang/en/docs/install/#debian-stable)
4. [JAVA](https://www.java.com/en/download/help/download_options.xml)
5. [Elasticsearch](https://www.elastic.co/downloads/elasticsearch)
### Installation and start-up instructions
1. Clone the project from the repository: `$ git clone https://github.com/sergio-santiago/task-assignment.git`
TODO

## Screenshots of some views
![1](https://image.ibb.co/d0CVUT/Captura_de_pantalla_de_2018_07_25_19_08_21.png) 
![2](https://image.ibb.co/kY8R3o/Captura_de_pantalla_de_2018_07_25_19_08_33.png)
![3](https://image.ibb.co/fDvTG8/Captura_de_pantalla_de_2018_07_25_19_08_50.png)
![4](https://image.ibb.co/k7uNb8/Captura_de_pantalla_de_2018_07_25_19_09_21.png)
![5](https://image.ibb.co/cA8tio/Captura_de_pantalla_de_2018_07_25_19_09_50.png)
![6](https://image.ibb.co/goiR3o/Captura_de_pantalla_de_2018_07_25_19_10_13.png)
![7](https://image.ibb.co/kZzNb8/Captura_de_pantalla_de_2018_07_25_19_10_28.png)
![8](https://image.ibb.co/gpG2b8/Captura_de_pantalla_de_2018_07_25_19_11_15.png)
![9](https://image.ibb.co/fStR3o/Captura_de_pantalla_de_2018_07_25_19_11_26.png)
![10](https://image.ibb.co/iuG2b8/Captura_de_pantalla_de_2018_07_25_19_11_46.png)
![11](https://image.ibb.co/eShKOo/Captura_de_pantalla_de_2018_07_25_19_12_04.png)
![12](https://image.ibb.co/fFcKOo/Captura_de_pantalla_de_2018_07_25_19_12_11.png)
![13](https://image.ibb.co/ktAH9T/Captura_de_pantalla_de_2018_07_25_19_14_00.png)
