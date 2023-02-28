# Webpack & Concrete5.8 Boilerplate ![CI status](https://img.shields.io/shippable/5444c5ecb904a4b21567b0ff/master.svg)

This is a new template for concrete5 projects that will be using webpack.

## Development Guidelines. Please strictly follow this.
[Guidelines]([Guidelines](https://docs.google.com/document/d/1U-RZHUkYiw8vAPHyR470E2t6AJ_KLHGKYdzohahkq_E/edit?usp=sharing))

## Default Credentials

```
Username: admin
Password: n2RDQpq7
```

## Installation

### Requirements

- NPM (Latest stable version if possible) [Open Link](https://nodejs.org/en/)

### Setting Up the project

- Open terminal on root folder (htdocs/projectname) then run the following commands

```sh
chmod 755 run_setup.sh
chmod 755 run_dev.sh
chmod 755 run_build.sh
chmod 755 install_package.sh
chmod 755 uninstall_package.sh
```

- Open `run_setup.sh`, `run_dev.sh`, `run_build.sh`, `install_package.sh`, `uninstall_package.sh`
- Change `theme` to your `Project Name`

- Once you're done setting permissions for bash files, run the command below to install npm packages.

```sh
./run_setup.sh
```

##### Important Note: Do this step _ONCE_ OR when there are _NEW PACKAGES_

- Then, open `.gitignore`. Search for `application/themes/theme/node_modules`
- Rename the `theme` folder

##### Important Note: Do this step _ONCE_ - This will ignore all the contents of `node_modules`

## Running the Project

- Open terminal on root folder (htdocs/projectname) then run the following commands

```sh
./run_dev.sh
```

## Installing a package

- Open terminal on root folder (htdocs/projectname) then run the following commands

```sh
./install_package.sh <package_name> '--save' or '--save-dev'
```

## Uninstalling a package

- Open terminal on root folder (htdocs/projectname) then run the following commands

```sh
./uninstall_package.sh <package_name> '--save' or '--save-dev'
```

## Build the Project

- Open terminal on root folder (htdocs/projectname) then run the following commands

```sh
./run_build.sh
```

## Javascript Code Guidelines for ES6 & Above

[Open Link](https://github.com/airbnb/javascript)

##Theme Folder Structure

```bash
|-- theme
    |-- .eslintrc
    |-- .eslintignore
    |-- babel.config.js
    |-- webpack.config.js
    |-- package.json
    |-- node_modules
    |-- elements
    |-- dist
        |-- css
            |-- app.min.css
        |-- images
        |-- js
            |-- app.min.js
            |-- vendor.min.js
    |-- src
        |-- fonts
        |-- images
        |-- js
            |-- components
            |-- external
            |-- app.js
        |-- scss
            |-- base
            |-- components
            |-- elements
            |-- fonts
            |-- mixins
            |-- main.scss
```

## 2.0.0 Changes

- Remove eslint
- Change devtool of production mode from eval to cheap-module-source-map
- Change splitChunks from all to async and remove vendor.min.js from scripts.php
- Fix css loading from node_modules
- Fix svg on css for font and images
- Compress scss into 2 folders (mixins, base)

## How to import styles from external libraries

- Slick

```
import 'slick-carousel';
import 'slick-carousel/slick/slick.css';
import 'slick-carousel/slick/slick-theme.css';
```

- Swiper

```
import Swiper from 'swiper';
import 'swiper/dist/css/swiper.min.css';
```

## How to integrate react

```
./install_package.sh react --save-dev
./install_package.sh react-dom --save-dev
./install_package.sh @babel/preset-react --save-dev
```

# Webpack config

```
{
    test: /\.js$/,
    exclude: /(node_modules|bower_components)/,
    use: {
        loader: "babel-loader",
        options: {
            presets: ["@babel/preset-env", "@babel/preset-react"],
        }
    }
}
```
