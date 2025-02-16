<script lang="ts">
    import type {IClothingList} from "../types/ClothingList";
    import type {IClothing} from "../types/Clothing";
    import ClothingCollection from "../components/ClothingCollection.svelte";
    import {createMutation} from "../utils/query";
    import {debounce} from "lodash-es";

    const props: {
        clothingList: IClothingList;
        clothingCollection: IClothing[];
    } = $props();

    const bookmark = props.clothingList;
    const clothingCollection = props.clothingCollection;


    const bookmarkMutation = createMutation(`/bookmark/delete/${bookmark.id}`, "DELETE");
    function onDelete() {
        console.log("delete");
        debounce($bookmarkMutation.mutate);
    }
</script>

<div class="space-y-4 p-4">
    <div>{bookmark.name}</div>
    <button on:click={onDelete}>Delete</button>
    <div>
        {#each clothingCollection as clothing}
            <div>
                <ClothingCollection {clothing} idBookmark={bookmark.id} />
            </div>
        {/each}
    </div>
</div>