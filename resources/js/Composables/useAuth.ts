import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

export function useAuth() {
    const page = usePage();

    const user = computed(() => page.props.auth.user);

    const hasRole = (role: string) => {
        return user.value?.roles?.includes(role) ?? false;
    };

    return {
        user,
        hasRole,
    };
}
