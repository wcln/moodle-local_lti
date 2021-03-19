module.exports = {
    publicPath: '/local/lti/vue/provider/dist/',
    configureWebpack: {
        resolve: {
            alias: {
                'vue$': 'vue/dist/vue.esm.js'
            }
        }
    },
    filenameHashing: false,
};
