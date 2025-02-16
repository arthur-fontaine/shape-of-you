<script lang="ts">

    import {createMutation} from "../utils/query.js";

    const bookmark = createMutation();
    function onSubmit(e: SubmitEvent) {
        const formData = new FormData(e.target as HTMLFormElement);
        const sentFormData = new FormData();
        sentFormData.append("name", formData.get("name") as string);
        sentFormData.append("isBookmark", formData.get("isBookmark") as string);
        console.log(formData.get("isBookmark"));
        $bookmark.mutate(sentFormData);
    }
</script>

<form on:submit|preventDefault={onSubmit}>
    <!-- MENU -->
    <input
        id="name"
        type="text"
        name="name"
        placeholder="Name"
    />
    <input
        id="isBookmark"
        type="checkbox"
        name="isBookmark"
        value="isBookmark"
    />
    <button type="submit">
        Create
    </button>
</form>