// this is from the bootstrap repo.

'use strict'

const path = require('node:path')
const { babel } = require('@rollup/plugin-babel')

const BUNDLE = process.env.BUNDLE === 'true'
const ESM = process.env.ESM === 'true'

let fileDestination = `finalium${ESM ? '.esm' : ''}`
const plugins = [
    babel({
        // Only transpile our source code
        exclude: 'node_modules/**',
        // Include the helpers in the bundle, at most one copy of each
        babelHelpers: 'bundled'
    })
]

if (BUNDLE) {
    fileDestination += '.bundle'
    plugins.push(
        replace({
            'process.env.NODE_ENV': '"production"',
            preventAssignment: true
        }),
        nodeResolve()
    )
}

const rollupConfig = {
    input: path.resolve(__dirname, `../assets/javascripts/index.${ESM ? 'esm' : 'umd'}.js`),
    output: {
        file: path.resolve(__dirname, `../dist/js/${fileDestination}.js`),
        format: ESM ? 'esm' : 'umd',
        generatedCode: 'es2015'
    },
    plugins
}

if (!ESM) {
    rollupConfig.output.name = 'finalium'
}

module.exports = rollupConfig
