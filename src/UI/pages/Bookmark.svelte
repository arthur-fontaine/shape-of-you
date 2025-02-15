<script lang="ts">
    import type {IClothingList} from "../types/ClothingList";
    import type {IClothing} from "../types/Clothing";

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
                {clothing.name}
            </div>
        {/each}
    </div>
</div>