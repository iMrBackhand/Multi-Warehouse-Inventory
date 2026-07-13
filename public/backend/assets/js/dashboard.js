document.addEventListener("DOMContentLoaded", function () {
    let chart;

    function loadPurchaseChart(year) {
        fetch("/admin/dashboard/purchase-chart/" + year)
            .then((response) => response.json())
            .then((data) => {
                if (chart) {
                    chart.destroy();
                }

                let options = {
                    series: [
                        {
                            name: "Total Purchase",
                            data: data,
                        },
                    ],
                    chart: {
                        height: 350,
                        type: "bar",
                        toolbar: { show: false },
                    },
                    colors: ["#6f42c1"],
                    plotOptions: {
                        bar: {
                            borderRadius: 8,
                            columnWidth: "40%",
                        },
                    },
                    fill: {
                        type: "gradient",
                        gradient: {
                            shade: "light",
                            type: "vertical",
                            gradientToColors: ["#8b5fd9"],
                            opacityFrom: 1,
                            opacityTo: 0.85,
                        },
                    },
                    dataLabels: { enabled: false },
                    xaxis: {
                        categories: [
                            "Jan",
                            "Feb",
                            "Mar",
                            "Apr",
                            "May",
                            "Jun",
                            "Jul",
                            "Aug",
                            "Sep",
                            "Oct",
                            "Nov",
                            "Dec",
                        ],
                    },
                    yaxis: {
                        min: 0,
                        forceNiceScale: true,
                        labels: {
                            formatter: function (value) {
                                return "₱" + value.toLocaleString();
                            },
                        },
                    },
                    tooltip: {
                        y: {
                            formatter: function (value) {
                                return "₱" + value.toLocaleString();
                            },
                        },
                    },
                    grid: { borderColor: "#f1f1f1" },
                };

                chart = new ApexCharts(
                    document.querySelector("#purchase-received-chart"),
                    options,
                );
                chart.render();

                fetch("/admin/dashboard/purchase-summary/" + year)
                    .then((response) => response.json())
                    .then((data) => {
                        document.getElementById("totalPurchaseCard").innerHTML =
                            "₱" + data.totalPurchase;
                    });
            });
    }

    // BAGO: mag-fetch ng sales summary base sa year, i-update yung Total Sales card
    function loadSalesSummary(year) {
        fetch("/admin/dashboard/sales-summary/" + year)
            .then((response) => response.json())
            .then((data) => {
                document.getElementById("totalSalesCard").innerHTML =
                    "₱" + data.totalSales;
            });
    }

    function loadYearData(year) {
        loadPurchaseChart(year);
        loadSalesSummary(year);
    }

    loadYearData(document.querySelector("#yearFilter").value);

    document
        .querySelector("#yearFilter")
        .addEventListener("change", function () {
            loadYearData(this.value);
        });
});
