export default {
    namespaced: true,
    state() {
        return {
            adminRole : ['admin'],
            agencyRole : ['admin','agency_owner', 'agency_staff'],
            companyRole : ['admin','agency_owner', 'agency_staff', 'company_owner', 'company_staff'],
            userManagementRole : ['admin', 'agency_owner', 'company_owner']
        }
    },
}
