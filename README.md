<p align="center">
    <h2>THE COHERENCE</h2>
</p>

[![License: AGPL v3](https://img.shields.io/badge/License-AGPL%20v3-blue.svg)](LICENSE-AGPL.md)
[![StyleCI](https://img.shields.io/badge/styleci-passing-green.svg)](https://your-styleci-link.com)

## Overview

This Laravel-based Efficiency and Project Management System is designed to streamline and automate various project management processes. The system encompasses comprehensive modules for managing employees, projects, tasks, clients, timesheets, and generating reports.

## Table of Contents

- [Features](#features)
- [Demo](#demo)
- [Installation](#installation)
- [Usage](#usage)
- [Contributing](#contributing)
- [License](#license)

## Features

- Users & Clients Management
- Projects Management
- Tasks Management
- Timesheet and Payroll Management
- Reports

## Demo

You can find a live demo of the project [here](https://coherence-opensource.buildsite.in/).

## Installation

To get started with the project, follow these steps:

### Prerequisites

- PHP >= 8.1
- Composer
- A web server (e.g., Apache, Nginx)
- MySQL

### Steps

1. **Clone the repository**

    ```sh
    git clone https://github.com/indy2hats/coherence-open-source.git
    cd coherence-open-source
    ```

2. **Install dependencies**

    ```sh
    composer install
    ```

3. **Environment configuration**

    Copy the `.env.example` file to `.env` and update your environment settings.

    ```sh
    cp .env.example .env
    ```

4. **Generate application key**

    ```sh
    php artisan key:generate
    ```

5. **Set up the database**

    Update your `.env` file with the correct database information and then run the migrations.

    ```sh
    php artisan migrate --seed
    ```

6. **Start the development server**

    ```sh
    php artisan serve
    ```

    Your application should now be running at `http://127.0.0.1:8000`.


7. **Inorder to use currency API, Generate the API key from APILayer Account and specify it on .env**

    - Sign Up for an APILayer Account(https://apilayer.com/marketplace/exchangerates_data-api).
    - Subscribe to the Exchange Rates Data API (There might be different pricing tiers, including a free tier with limited usage).
    - Obtain Your API Key.
    - Update your `.env` file with the following line:

    ```env
    CURRENCY_API_KEY=your_api_key_here
    ```

8. **Specify Email configurations in .env**

## Usage

For detailed usage instructions, please refer to the [USAGE.md](USAGE.md) file.

## Security

If you've found a bug regarding security, please send an e-mail to info@2hatslogic.com instead of using the issue tracker. All security vulnerabilities will be promptly addressed.

## Code of Conduct

In order to ensure that the The Coherence community is welcoming to all, please review and abide by the [Code of Conduct](./CODE_OF_CONDUCT.md).

## Contributing

Thank you for considering contributing to THE COHERENCE!

We welcome contributions of all kinds, from bug reports and feature requests to code improvements and documentation enhancements.

This document outlines the steps and expectations for contributing to this project. Please see [contributing and developing](./CONTRIBUTING.md) guidelines for The Coherence.

## Thankware

You're free to use this package, but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

We publish all received postcards on our company website(with permission, of course!).

Alternatively, you can send us an email at info@2hatslogic.com regarding this to let us know which of our package(s) you are using.

## License

This project is available under dual licensing terms. You can choose to use it under one of the following licenses:

- **Open Source License**: [GNU Affero General Public License (AGPL) v3](./LICENSE-AGPL.md)
- **Commercial License**: [Commercial License Agreement](./LICENSE-COMMERCIAL.md)

### Open Source License (AGPL v3)

The AGPL v3 license allows you to use, modify, and distribute the software, provided that you adhere to the terms of the AGPL. This includes making your source code available and ensuring that any derivative works are also licensed under the AGPL.

For more details, see the [AGPL v3 License](./LICENSE-AGPL.md).

### Commercial License

If you prefer to use this software without the obligations of the AGPL, you can obtain a commercial license. The commercial license allows you to use, modify, and distribute the software without having to make your source code available or adhere to the AGPL's requirements.
The commercial license offers several advantages, including:

    - Additional features: Benefit from exclusive features like billing and invoicing modules, Gantt charts, and financial reports.
    - Dedicated support: Access to our expert support team for assistance and troubleshooting.
    - Priority bug fixes and Customizations.

For more details and to obtain a commercial license, see the [Commercial License Agreement](./LICENSE-COMMERCIAL.md) or contact us at info@2hatslogic.com.

## How to Choose

- **For Open Source Projects**: Use the AGPL license.
- **For Commercial Use**: Obtain a commercial license.
