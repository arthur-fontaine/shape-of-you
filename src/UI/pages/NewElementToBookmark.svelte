<script lang="ts">
    import type {IClothingList} from "../types/ClothingList";

    const props: {
        clothingList: IClothingList[];
        clothingId: string;
    } = $props();

    const clothingList = props.clothingList;
    const clothingId = props.clothingId;
</script>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold">Ajouter un élément à une collection</h1>
    <form action="/bookmark/add" method="POST">
        <label for="collection">Sélectionner une collection:</label>
        <select id="collection" name="collection">
            {#each clothingList as collection}
                <option value={collection.id}>{collection.name}</option>
            {/each}
        </select>
        <button type="submit">Ajouter</button>
    </form>
</div>