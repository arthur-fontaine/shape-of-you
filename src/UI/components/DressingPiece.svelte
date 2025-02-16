<script lang="ts">

    import type {IDressing} from "../types/Dressing";



    const props: {
        dressing: IDressing;
    } = $props();

    const clothing = props.dressing.clothing;
    const dressing = props.dressing;


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
          {dressing.comment}
      </span>
    </div>
</div>
