if (window.iconify) {
    import("iconify-icon");
}

if (window.ApexCharts) {
    import("apexcharts").then((module) => {
        window.ApexCharts = module.default;
    });
}
