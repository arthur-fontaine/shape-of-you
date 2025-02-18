<script lang="ts">

    import type {IDressing} from "../types/Dressing";
    import {createMutation} from "../utils/query";



    const props: {
        dressing: IDressing;
    } = $props();

    const clothing = props.dressing.clothing;
    const dressing = props.dressing;


    const dressingMutation = createMutation<unknown, {
        clothingId: number;
    }> (`/dressing/remove`);
    function onDelete() {
        $dressingMutation.mutate({
            clothingId: clothing.id
        });
    }

</script>

<div class="mb-3 bg-white shadow-md rounded-lg overflow-hidden">
    <div class="p-4">
        <h3 class="text-lg font-semibold">{clothing.name}</h3>
        <button on:click={onDelete}>Delete</button>
    </div>
    <img src={clothing.imageUrl} alt="Post" class="w-full h-auto" />
    <div class="p-4 flex items-center justify-between">
      <span class="relative">
        <span
                class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-full bg-white px-1 text-sm w-min whitespace-nowrap"
        >
        </span>
          {dressing.comment}
      </span>
    </div>
</div>
