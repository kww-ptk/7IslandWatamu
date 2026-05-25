/** @type {import('tailwindcss').Config} */
export default {
  theme: {
    extend: {
    colors: {
        primary: {
            '50': 'hsl(29, 73%, 97%)',
            '100': 'hsl(29, 73%, 94%)',
            '200': 'hsl(29, 73%, 86%)',
            '300': 'hsl(29, 73%, 76%)',
            '400': 'hsl(29, 73%, 64%)',
            '500': 'hsl(29, 73%, 50%)',
            '600': 'hsl(29, 73%, 40%)',
            '700': 'hsl(29, 73%, 32%)',
            '800': 'hsl(29, 73%, 24%)',
            '900': 'hsl(29, 73%, 16%)',
            '950': 'hsl(29, 73%, 10%)',
            DEFAULT: '#e49951'
        },
        secondary: {
            '50': 'hsl(196, 56%, 97%)',
            '100': 'hsl(196, 56%, 94%)',
            '200': 'hsl(196, 56%, 86%)',
            '300': 'hsl(196, 56%, 76%)',
            '400': 'hsl(196, 56%, 64%)',
            '500': 'hsl(196, 56%, 50%)',
            '600': 'hsl(196, 56%, 40%)',
            '700': 'hsl(196, 56%, 32%)',
            '800': 'hsl(196, 56%, 24%)',
            '900': 'hsl(196, 56%, 16%)',
            '950': 'hsl(196, 56%, 10%)',
            DEFAULT: '#153d4b'
        },
        accent: {
            '50': 'hsl(0, 100%, 97%)',
            '100': 'hsl(0, 100%, 94%)',
            '200': 'hsl(0, 100%, 86%)',
            '300': 'hsl(0, 100%, 76%)',
            '400': 'hsl(0, 100%, 64%)',
            '500': 'hsl(0, 100%, 50%)',
            '600': 'hsl(0, 100%, 40%)',
            '700': 'hsl(0, 100%, 32%)',
            '800': 'hsl(0, 100%, 24%)',
            '900': 'hsl(0, 100%, 16%)',
            '950': 'hsl(0, 100%, 10%)',
            DEFAULT: '#ff0000'
        },
        'neutral-50': '#ffffff',
        'neutral-100': '#000000',
        'neutral-200': '#838a8d',
        'neutral-300': '#e0dfd8',
        'neutral-400': '#eeeeee',
        'neutral-500': '#ccd6df',
        background: '#f2f2ec',
        foreground: '#000000'
    },
    fontFamily: {
        sans: [
            'Jost',
            'sans-serif'
        ],
        heading: [
            'Ortica Linear',
            'sans-serif'
        ],
        body: [
            'amoja-icon',
            'sans-serif'
        ]
    },
    fontSize: {
        '20': [
            '20px',
            {
                lineHeight: '26px',
                letterSpacing: '2px'
            }
        ],
        '22': [
            '22px',
            {
                lineHeight: '34.1px'
            }
        ],
        '24': [
            '24px',
            {
                lineHeight: '38.4px',
                letterSpacing: '-1px'
            }
        ],
        '25': [
            '25px',
            {
                lineHeight: '25px'
            }
        ],
        '26': [
            '26px',
            {
                lineHeight: '39px'
            }
        ],
        '32': [
            '32px',
            {
                lineHeight: '38px',
                letterSpacing: '-1px'
            }
        ],
        '36': [
            '36px',
            {
                lineHeight: '39.8571px',
                letterSpacing: '-1px'
            }
        ],
        '42': [
            '42px',
            {
                lineHeight: '60px',
                letterSpacing: '-1px'
            }
        ],
        '56': [
            '56px',
            {
                lineHeight: '62px',
                letterSpacing: '-1px'
            }
        ],
        '60': [
            '60px',
            {
                lineHeight: '60px'
            }
        ],
        '64': [
            '64px',
            {
                lineHeight: '64px'
            }
        ],
        '74': [
            '74px',
            {
                lineHeight: '77.7px',
                letterSpacing: '-1px'
            }
        ],
        '80': [
            '80px',
            {
                lineHeight: '80px'
            }
        ],
        '96': [
            '96px',
            {
                lineHeight: '96px',
                letterSpacing: '-2px'
            }
        ],
        '110': [
            '110px',
            {
                lineHeight: '110px',
                letterSpacing: '-2px'
            }
        ]
    },
    spacing: {
        '7': '35px',
        '10': '50px',
        '11': '55px',
        '12': '60px',
        '13': '65px',
        '14': '70px',
        '15': '75px',
        '16': '80px',
        '20': '100px',
        '22': '110px',
        '24': '120px',
        '28': '140px',
        '30': '150px',
        '42': '210px',
        '46': '230px',
        '1px': '1px',
        '24px': '24px',
        '192px': '192px',
        '417px': '417px'
    },
    borderRadius: {
        sm: '4px',
        md: '10px',
        lg: '15px',
        full: '50px'
    },
    boxShadow: {
        sm: 'rgb(242, 242, 236) 0px 0px 0px 15px',
        xl: 'rgba(0, 0, 0, 0.1) 0px 4px 30px 0px'
    },
    screens: {
        '410px': '410px',
        sm: '601px',
        md: '783px',
        '881px': '881px',
        lg: '1025px',
        '1201px': '1201px',
        '1367px': '1367px',
        '1400px': '1400px'
    },
    transitionDuration: {
        '0': '0s',
        '200': '0.2s',
        '250': '0.25s',
        '300': '0.3s',
        '350': '0.35s',
        '400': '0.4s',
        '500': '0.5s',
        '600': '0.6s',
        '700': '0.7s',
        '750': '0.75s',
        '1000': '1s',
        '1500': '1.5s'
    },
    transitionTimingFunction: {
        custom: 'cubic-bezier(0.65, 0.05, 0.36, 1)',
        default: 'ease',
        linear: 'linear'
    },
    container: {
        center: true,
        padding: '0px'
    },
    maxWidth: {
        container: '100%'
    }
},
  },
};
