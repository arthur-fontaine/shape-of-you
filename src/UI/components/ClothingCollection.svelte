<script lang="ts">

    import type {IClothing} from "../types/Clothing";
    import {createMutation} from "../utils/query";

    const props: {
        clothing: IClothing;
        idBookmark: number;
    } = $props();

    const clothing = props.clothing;
    const idBookmark = props.idBookmark;

    const bookmarkMutation = createMutation<unknown, {
        clothingId: number;
    }> (`/profile/bookmarks/${idBookmark}/remove-item`);

    function onDelete() {
        $bookmarkMutation.mutate({
            clothingId: clothing.id
        });
    }

</script>
<div class="mb-3 bg-white shadow-md rounded-lg overflow-hidden">
    <div class="p-4">
        <h3 class="text-lg font-semibold">{clothing.name}</h3>
    </div>
    <img src={clothing.imageUrl} alt="Post" class="w-full h-auto" />
    <div class="p-4 flex items-center justify-between">
      <span class="relative">
        <span
                class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-full bg-white px-1 text-sm w-min whitespace-nowrap"
        >
        </span>
          <button on:click={onDelete}>Delete</button>
      </span>
    </div>
</div>
