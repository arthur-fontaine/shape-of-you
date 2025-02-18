<script lang="ts">
    import type {IDressing} from "../types/Dressing";
    import DressingPiece from "../components/DressingPiece.svelte";

    const props: {
        dressing: IDressing[];
    } = $props();

    const dressingPiece = props.dressing;
    console.log(dressingPiece);

</script>

<div class="space-y-4 p-4">
    <div>
        {#each dressingPiece as dressingClothing}
            <div>
                <DressingPiece dressing={dressingClothing} />
            </div>
        {/each}
    </div>
</div>