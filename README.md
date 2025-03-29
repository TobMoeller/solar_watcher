# Solar Watcher

![application overview](/docs/overview.png)

**Solar Watcher** is a Laravel-based frontend and API application hosted on a VPS at [solar.moellerweid.de](https://solar.moellerweid.de). It serves as the complementary application to the backend service [solar_logger](https://github.com/TobMoeller/solar_logger).

## Core Functionalities

- **Versioned REST API**: Provides a secure, structured, and versioned REST API used by Solar Logger for data synchronization.
- **Data Visualization**: Presents solar data in an interactive, publicly accessible dashboard, built using Laravel Livewire.
- **Dynamic Data Grouping**: Allows data visualization grouped by inverter or cumulative overview.
- **Interactive Charts**: Employs custom Alpine.js and Laravel Livewire components integrated with Chart.js for dynamic graphical representations.
  - **Annual & Monthly Energy Reports**: Displays generated energy as bar charts, aggregated monthly or daily in kWh.
  - **Detailed Daily Analysis**: Line charts depicting the daily progression of current (A), voltage (V), and power (W) at 5-minute intervals.

## Screenshots

![application dashboard](/docs/screenshot1.jpg)
![monthly chart](/docs/screenshot2.jpg)
![daily chart](/docs/screenshot3.jpg)

