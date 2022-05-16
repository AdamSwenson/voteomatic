
<script>
import ComponentCompilerParent from './component-compiler-parent';
import InsertPending from "./text-stylers/insert-pending";
import InsertFailed from "./text-stylers/insert-failed";
import TextStylerFactory from "./text-stylers/text-styler-factory";

export default {
    name: "compiled-rezzie-text",
    components: {TextStylerFactory, InsertFailed, InsertPending},
    extends : ComponentCompilerParent


}
</script>

<style scoped>

</style>
