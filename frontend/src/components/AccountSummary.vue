<template>
    <div class="account-summary">
        <v-simple-table>
            <template v-slot:default>
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Budget</th>
                        <th>Actual</th>
                        <th>Encumbrance</th>
                        <th>Available Balance</th>
                        <th>Additional Encumbrance</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="summary in summaryDetails"
                        :key="summary.name"
                    >
                        <td>{{ summary.name }}</td>
                        <td>{{ summary.budget | currency }}</td>
                        <td>{{ summary.actual | currency }}</td>
                        <td>{{ summary.encumbrance | currency }}</td>
                        <td>({{ summary.available_balance | currency }})</td>
                        <td>{{ summary.additional_encumbrance | currency }}</td>
                    </tr>
                </tbody>
            </template>
        </v-simple-table>
    </div>
</template>


<script>
export default {
    data: () => ({}),
    props: ['summaryDetails']
}
</script>