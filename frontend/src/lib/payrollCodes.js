const code_lookup = {
    '5111': 'Regular Payroll – Faculty',
    '5112': 'Regular Payroll – Other professional',
    '5231': 'Payroll – Contractual',
    '5232': 'Payroll – Faculty Summer',
    '5240': 'Payroll – Student Labor',
    '5250': 'Payroll – Graduates Students',
    '5260': 'Payroll – Post Doctors'
}

export const PAYROLL_CODE_LOOKUP = code_lookup

export function getPayrollLabelForCode (code) {
    if ( code in code_lookup ) {
        return code_lookup[code]
    }
    return '(No Payroll Label)'
}