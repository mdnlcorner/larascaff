import Chart from 'chart.js/auto';
import ChartDataLabels, { Context } from 'chartjs-plugin-datalabels';

type StatType = {
    data: [];
    color: string;
    type: 'statistic';
};

type DatasetsType = Array<{
    fill: string;
    data: number[];
    backgroundColor: string;
    borderColor: string;
    borderWidth: number;
    tension?: number;
    pointBackgroundColor?: string;
}>;

type ChartType = {
    labels: Array<string>;
    data: [];
    color: string;
    type: 'chart';
    datasets: DatasetsType;
    dataLabel?: boolean;
};

export default function initChart({ color, ...config }: StatType | ChartType) {
    return {
        init: function () {
            const colorVariants = {}
            for (let [key, value] of Object.entries(JSON.parse(document.querySelector('[data-color-variants]')?.innerHTML ?? '{}'))) {
                colorVariants[key] = {
                    backgroundColor: 'rgba(' + value + ', 0.2)',
                    borderColor: 'rgba(' + value + ')',
                }
            }

            if (config.type == 'statistic') {
                new Chart(this.$refs.canvas, {
                    type: 'line',
                    options: {
                        animation: {
                            duration: 0,
                        },
                        elements: {
                            point: {
                                radius: 0,
                            },
                        },
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                display: false,
                            },
                            y: {
                                display: false,
                            },
                        },
                        plugins: {
                            legend: {
                                display: false,
                            },
                            tooltip: {
                                enabled: false,
                            },
                        },
                    },
                    data: {
                        labels: config.data,
                        datasets: [
                            {
                                fill: 'start',
                                data: config.data,
                                backgroundColor: [colorVariants[color].backgroundColor],
                                borderColor: [colorVariants[color].borderColor],
                                borderWidth: 2,
                                tension: 0.5,
                            },
                        ],
                    },
                });
            } else if (config.type == 'chart') {
                const datasets = config.datasets.map((item) => {
                    item.backgroundColor = item.backgroundColor ? colorVariants[item.backgroundColor].backgroundColor : colorVariants[color].backgroundColor;
                    item.borderColor = item.borderColor ? colorVariants[item.borderColor].borderColor : colorVariants[color].borderColor;
                    item.borderWidth = item.borderWidth ?? 2;
                    item.tension = item.tension ?? 0.3;
                    item.pointBackgroundColor = item.pointBackgroundColor
                        ? colorVariants[item.pointBackgroundColor].borderColor
                        : colorVariants[color].borderColor;
                    return item;
                });

                let plugins: Array<any> = [];
                let pluginsConfig: any = {};

                if (config.dataLabel) {
                    plugins.push(ChartDataLabels);
                    pluginsConfig.datalabels = {
                        display: true,
                        align: 'end',
                        anchor: 'end',
                        color: function (ctx: any) {
                            return 'white';
                        },
                        borderColor: function (ctx: Context) {
                            return ctx.dataset.borderColor as string;
                        },
                        borderWidth: 2,
                        borderRadius: (ctx: Context) => {
                            return ctx.active ? 5 : 20;
                        },
                        padding: 3,
                        backgroundColor: (ctx: Context) => {
                            return ctx.dataset.borderColor as string;
                        },
                        font: (ctx: Context) => {
                            return {
                                weight: ctx.active ? 'bold' : 'normal',
                                size: ctx.active ? 14 : 10,
                            };
                        },
                    };
                }

                new Chart(this.$refs.canvas, {
                    type: 'line',
                    plugins: plugins,
                    options: {
                        responsive: true,
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: { display: true, position: 'bottom' },
                            ...pluginsConfig,
                        },
                    },
                    data: {
                        labels: config.labels,
                        datasets: datasets,
                    },
                });
            }
        },
    };
}

window['initChart'] = initChart;
