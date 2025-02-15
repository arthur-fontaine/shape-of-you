<script lang="ts">
    import type {IClothingList} from "../types/ClothingList";
    import type {IClothing} from "../types/Clothing";
    import ClothingCollection from "../components/ClothingCollection.svelte";

    const props: {
        clothingList: IClothingList;
        clothingCollection: IClothing[];
    } = $props();

    const bookmark = props.clothingList;
    const clothingCollection = props.clothingCollection;
    console.log(clothingCollection);
</script>

<div class="space-y-4 p-4">
    <div>{bookmark.name}</div>
    <div>
        {#each clothingCollection as clothing}
            <div>
                <ClothingCollection {clothing} />
            </div>
        {/each}
    </div>
</div>