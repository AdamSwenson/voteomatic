<script>
// Cool way to render Vue components from HTML Strings
// https://medium.com/haiiro-io/compile-markdown-as-vue-template-on-nuxt-js-1c606c15731c
import VueWithCompiler from "vue/dist/vue.esm";

export default {
    name: "component-compiler-parent",

    props: {
        html: {
            type: String,
            default: "",
        },
    },
    data() {
        return {templateRender: undefined};
    },

    watch: {
        html(to) {
            this.updateRender();
            // this.$nextTick(function () {
            // this.initializePopovers();

        },
    },

    created() {
        this.updateRender();
    },

    methods: {
        updateRender() {
            let html = '<div class="rezzie">' + this.html + '</div>';
            const compiled = VueWithCompiler.compile(html);
            this.templateRender = compiled.render;
            this.$options.staticRenderFns = [];
            for (const staticRenderFunction of compiled.staticRenderFns) {
                this.$options.staticRenderFns.push(staticRenderFunction);
            }


        },

        initializePopovers() {
            this.$nextTick(function () {
                var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
                var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                    return new bootstrap.Popover(popoverTriggerEl)
                });
                window.console.log('popovers', popoverTriggerList,);
            });
        }
    },

    render() {
        return this.templateRender();
    },
};
</script>


<style scoped>

</style>
