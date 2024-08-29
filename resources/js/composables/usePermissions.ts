import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Role } from "@/types/roleTypes";

export function usePermissions() {
    // Access the permissions and roles from the page props
    const page = usePage();

    // Correctly type the computed properties
    const permissions = computed(() => page.props.userPermissions as string[]);
    // const roles = computed(() => page.props.userRoles as Role[]);

    const roles = computed<Role[]>(() => {
        const userRoles = page.props.userRoles;

        // Ensure userRoles is an array
        if (Array.isArray(userRoles)) {
            return userRoles;
        } else if (userRoles) {
            return [userRoles]; // Wrap single role object in an array
        } else {
            return [];
        }
    });

    // Function to check if the user has a specific permission
    function can(permission: string): boolean {
        return permissions.value.includes(permission);
    }

    // Function to check if the user has a specific role by its name
    function hasRole(roleName: string): boolean {
        return roles.value.some(role => role.name === roleName);
    }

    // Function to check if the user has any of the specified roles
    function hasAnyRole(roleNames: string[]): boolean {
        return roleNames.some(roleName => roles.value.some(role => role.name === roleName));
    }

    // Function to check if the user has all of the specified roles
    function hasAllRoles(roleNames: string[]): boolean {
        return roleNames.every(roleName => roles.value.some(role => role.name === roleName));
    }

    // Function to check if the user has all of the specified permissions
    function hasAllPermissions(permissionsToCheck: string[]): boolean {
        return permissionsToCheck.every(permission => permissions.value.includes(permission));
    }

    // Function to check if the user has any of the specified permissions
    function hasAnyPermission(permissionsToCheck: string[]): boolean {
        return permissionsToCheck.some(permission => permissions.value.includes(permission));
    }

    return {
        can,
        hasRole,
        hasAnyRole,
        hasAllRoles,
        hasAllPermissions,
        hasAnyPermission
    };
}
