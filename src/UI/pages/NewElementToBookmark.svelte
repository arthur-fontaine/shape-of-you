<script lang="ts">
    import type {IClothingList} from "../types/ClothingList";
    import {createMutation} from "../utils/query";

    const props: {
        clothingList: IClothingList[];
        clothingId: string;
    } = $props();

    const clothingList = props.clothingList;
    const clothingId = props.clothingId;

    console.log(clothingId);
    const bookmark = createMutation();
    function onSubmit(e: SubmitEvent) {
        const formData = new FormData(e.target as HTMLFormElement);
        const sentFormData = new FormData();
        sentFormData.append("collection", formData.get("collection") as string);
        sentFormData.append("clothingId", clothingId);
        console.log(formData.get("collection"));
        $bookmark.mutate(sentFormData);
    }
</script>

<form on:submit|preventDefault={onSubmit}>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold">Ajouter un élément à une collection</h1>
            <label for="collection">Sélectionner une collection:</label>
            <select id="collection" name="collection">
                {#each clothingList as collection}
                    <option value={collection.id}>{collection.name}</option>
                {/each}
            </select>
            <button type="submit">Ajouter</button>
    </div>
</form>