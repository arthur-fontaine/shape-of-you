<script lang="ts">
    import type { IUser } from "../types/User";
    import profile from "../assets/icons/profile.svg";
    import Avatar from "svelte-boring-avatars";

    const {
        user,
    }: {
        user: IUser;
    } = $props();
</script>

<div class="bg-white shadow-md">
    <div class="max-w-5xl mx-auto p-4 pb-0">
        <div class="flex items-center space-x-4">
            <Avatar
                size={64}
                name={user.name}
                variant="beam"
                colors={["#92A1C6", "#146A7C", "#F0AB3D", "#C271B4", "#C20D90"]}
            />
            <div class="grid grid-cols-2 gap-x-4 gap-y-0.5">
                <h2 class="text-xl font-bold col-span-2">{user.name}</h2>
                <span
                    ><span class="font-bold">{user.posts.length}</span> posts</span
                >
                <span
                    ><span class="font-bold">{user.friends.length}</span> followers</span
                >
            </div>
        </div>
        <div
            class="mt-8 flex *:flex-1 *:justify-center *:text-2xl *:flex *:pb-4"
        >
            <a
                href="/profile"
                aria-label="Profile"
                class={location.pathname === "/profile"
                    ? "border-b-2 border-b-label"
                    : "text-disabled"}
            >
                <span class="icon-[tabler--layout-grid]"></span>
            </a>
            <a
                href="/profile/bookmarks"
                aria-label="Bookmarks"
                class={location.pathname === "/profile/bookmarks"
                    ? "border-b-2 border-b-label"
                    : "text-disabled"}
            >
                <span class="icon-[tabler--bookmark]"></span>
            </a>
            <a
                href="/profile/dressing"
                aria-label="Dressing"
                class={location.pathname === "/profile/dressing"
                    ? "border-b-2 border-b-label"
                    : "text-disabled"}
            >
                <span class="icon-[tabler--hanger]"></span>
            </a>
        </div>
    </div>
</div>
