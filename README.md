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
1. [XAMPP](https://www.apachefriends.org/es/index.html)
2. [Composer](https://getcomposer.org/download/)
3. [Node.js](https://nodejs.org/en/download/)
4. [YARN](https://yarnpkg.com/lang/en/docs/install/#windows-stable)
5. [JAVA](https://www.java.com/es/download/)
6. [Elasticsearch](https://www.elastic.co/downloads/elasticsearch)
### Installation and start-up instructions
1. Clone the project from the repository: `$ git clone https://github.com/sergio-santiago/task-assignment.git`
2. In the project directory run the `composer install` command to install the composer dependencies
3. Run the `yarn install` command to install YARN dependencies
4. Make sure you have MySQL up, in the next steps you will establish connection with it
5. Configure the `.env` file with the values to create the database. You should modify this line ```DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name```
6. Execute the `php bin/console doctrine:database:create` command to create the database with the values configured in the previous step
7. Execute the `php bin/console doctrine:migrations:migrate` command to create tables in the database
8. Execute the command `yarn run encore dev` to compile the assets of the project
9. Execute the `php bin/console app:create:admin` command to start the wizard that will help you create a user with which to access the application
10. Run the project with the command `php bin/console server:run`
11. Access with your browser to the address http://127.0.0.1:8000
12. You can now access the system with the user that you have previously created
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
