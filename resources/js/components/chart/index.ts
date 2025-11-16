import Chart, { ChartTypeRegistry } from 'chart.js/auto';

type StatType = {
    data: [];
    color: string;
    widgetType: 'statistic';
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
    color: string;
    widgetType: 'chart';
    type: keyof ChartTypeRegistry;
    datasets: DatasetsType;
    dataLabel?: boolean;
};

export default function initChart({ color, ...config }: StatType | ChartType) {
    return {
        init: function () {
            const colorVariants = {};
            for (const [key, value] of Object.entries(JSON.parse(document.querySelector('[data-color-variants]')?.innerHTML ?? '{}'))) {
                colorVariants[key] = {
                    backgroundColor: 'rgba(' + value + ', 0.2)',
                    borderColor: 'rgba(' + value + ')',
                };
            }

            if (config.widgetType == 'statistic') {
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
            } else if (config.widgetType == 'chart') {
                const datasets = config.datasets.map((item) => {
                    item.backgroundColor = item.backgroundColor
                        ? colorVariants[item.backgroundColor].backgroundColor
                        : colorVariants[color].backgroundColor;
                    item.borderColor = item.borderColor ? colorVariants[item.borderColor].borderColor : colorVariants[color].borderColor;
                    item.borderWidth = item.borderWidth ?? 2;
                    item.tension = item.tension ?? 0.3;
                    item.pointBackgroundColor = item.pointBackgroundColor
                        ? colorVariants[item.pointBackgroundColor].borderColor
                        : colorVariants[color].borderColor;
                    return item;
                });

                const plugins: Array<any> = [];
                const pluginsConfig: any = {};

                (window['chartjsPlugins'] ?? []).forEach((item: any) => {
                    plugins.push(item);
                });

                for (const [key, value] of Object.entries(window['chartjsPluginsConfig'] ?? {})) {
                    pluginsConfig[key] = value;
                }

                new Chart(this.$refs.canvas, {
                    type: config.type,
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
