<script lang="ts">
    import type {IClothingList} from "../types/ClothingList";
    import type {IClothing} from "../types/Clothing";
    import ClothingCollection from "../components/ClothingCollection.svelte";
    import {createMutation} from "../utils/query";

    const props: {
        clothingList: IClothingList;
        clothingCollection: IClothing[];
    } = $props();

    const bookmark = props.clothingList;
    const clothingCollection = props.clothingCollection;


    console.log(bookmark.id);
    const bookmarkMutation = createMutation<unknown, {
        bookmarkId: number;
    }> (`/bookmarks/delete`);
    function onDelete() {
        $bookmarkMutation.mutate({
            bookmarkId: bookmark.id
        });
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