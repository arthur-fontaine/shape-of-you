<script lang="ts">
    import {createMutation} from "../utils/query";

    const props: {
        clothingId: number;
    } = $props();

    const clothingId = props.clothingId;

    const dressingMutation = createMutation(`/dressing/add`, "POST");

    function onSubmit(e: SubmitEvent) {
        const formData = new FormData(e.target as HTMLFormElement);
        const sentFormData = new FormData();
        sentFormData.append("rate", formData.get("rate") as string);
        sentFormData.append("comment", formData.get("comment") as string);
        sentFormData.append("clothingId", clothingId.toString());
        $dressingMutation.mutate(sentFormData);
    }

</script>

<form on:submit|preventDefault={onSubmit}>
    <!-- MENU -->
    <input
            id="name"
            type="text"
            name="rate"
            placeholder="rate"
    />
    <input
            id="isBookmark"
            type="text"
            name="comment"
            value="comment"
    />
    <button type="submit">
        Create
    </button>
</form>