import * as esbuild from 'esbuild'
import { sassPlugin } from 'esbuild-sass-plugin'
const isDev = process.argv.includes('--dev')

async function compile(options) {
    const context = await esbuild.context(options)

    if (isDev) {
        await context.watch()
    } else {
        await context.rebuild()
        await context.dispose()
    }
}

const defaultOptions = {
    define: {
        'process.env.NODE_ENV': isDev ? `'development'` : `'production'`,
    },
    bundle: true,
    mainFields: ['module', 'main'],
    platform: 'neutral',
    sourcemap: isDev ? 'inline' : false,
    sourcesContent: isDev,
    treeShaking: true,
    target: ['es2020'],
    minify: !isDev,
    external: ['require', 'fs', 'path'],
    loader: {
        '.jpg': 'dataurl',
        '.png': 'dataurl',
        '.svg': 'text',
        '.gif': 'dataurl',
        '.woff': 'file',
        '.woff2': 'file',
        '.data': 'base64',
    },
    plugins: [
        {
            name: 'watchPlugin',
            setup: function (build) {
                build.onStart(() => {
                    console.log(
                        `Build started at ${new Date(
                            Date.now(),
                        ).toLocaleTimeString()}: ${build.initialOptions.outfile
                        }`,
                    )
                })

                build.onEnd((result) => {
                    if (result.errors.length > 0) {
                        console.log(
                            `Build failed at ${new Date(
                                Date.now(),
                            ).toLocaleTimeString()}: ${build.initialOptions.outfile
                            }`,
                            result.errors,
                        )
                    } else {
                        console.log(
                            `Build finished at ${new Date(
                                Date.now(),
                            ).toLocaleTimeString()}: ${build.initialOptions.outfile
                            }`,
                        )
                    }
                })
            },
        },
        sassPlugin()
    ],
}
const components = [
    'datatable',
    'chart',
    'datepicker-range',
    'accordion',
    'richeditor',
    'markdown-editor'
];

const componentsJs = [
    'select',
    'file-upload',
    'datepicker',
];

componentsJs.forEach(component => {
    compile({
        ...defaultOptions,
        entryPoints: [
            `./resources/js/components/${component}/index.js`,
        ],
        outfile: `./dist/components/${component}.js`,
    })
})

components.forEach(component => {
    compile({
        ...defaultOptions,
        entryPoints: [
            `./resources/js/components/${component}/index.ts`,
        ],
        outfile: `./dist/components/${component}.js`,
    })
})

const vendors = ['alpine']

vendors.forEach(vendor => {
    compile({
        ...defaultOptions,
        entryPoints: [
            `./resources/js/vendor/${vendor}/index.ts`,
        ],
        outfile: `./dist/js/vendor/${vendor}.js`,
    })
})

compile({
    ...defaultOptions,
    entryPoints: [
        `./resources/scss/app.scss`,
    ],
    outfile: `./dist/css/app.css`,
})

compile({
    ...defaultOptions,
    entryPoints: [
        `./resources/js/bootstrap.ts`,
    ],
    outfile: `./dist/js/bootstrap.js`,
})