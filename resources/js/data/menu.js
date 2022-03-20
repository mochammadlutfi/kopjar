/*
 * Main and demo navigation arrays
 */

export default {
    'main': [
      {
        name: 'Dashboard',
        to: 'dashboard',
        icon: 'si si-speedometer'
      },
      {
        name: 'Anggota',
        icon: 'si si-user',
        subActivePaths: 'anggota.*',
        sub: [
            {
              name: 'Tambah Anggota Baru',
              to: 'anggota.create',
            },
            {
              name: 'List Anggota',
              to: 'anggota.index',
            },
        ]
      },
      {
        name: 'Simpanan',
        icon: 'si si-wallet',
        subActivePaths: 'simpanan.*',
        sub: [
            {
              name: 'Wajib',
              subActivePaths: 'simpanan.wajib.*',
              sub: [
                  {
                    name: 'Setoran',
                    to: 'simpanan.wajib.index',
                    badge : 10
                  },
                  {
                    name: 'Tunggakan',
                    to: 'simpanan.wajib.tunggakan',
                  },
              ]
            },
            {
              name: 'Sukarela',
              subActivePaths: 'simpanan.sukarela.*',
              sub: [
                  {
                    name: 'Setoran',
                    to: 'simpanan.sukarela.index',
                    subActivePaths: 'simpanan.sukarela.deposit*',
                  },
                  {
                    name: 'Penarikan',
                    to: 'simpanan.sukarela.withdraw.index',
                    subActivePaths: 'simpanan.sukarela.withdraw.deposit*',
                  },
              ]
            },
        ]
      },
      
      {
        name: 'Pembiayaan',
        icon: 'si si-wallet',
        subActivePaths: 'pembiayaan.*',
        sub: [
            {
              name: 'Tunai',
              subActivePaths: 'pembiayaan.tunai.*',
              sub: [
                    {
                        name: 'Pengajuan',
                        to: 'pembiayaan.tunai.index',
                    },
                    {
                        name: 'Pembayaran',
                        to: 'pembiayaan.tunai.transaksi.index',
                    },
              ]
            },
        ]
      },
      {
        name: 'Pengaturan',
        icon: 'si si-wrench',
        subActivePaths: 'settings.*',
        sub: [
            {
              name: 'Staff',
              to: 'settings.staff.index',
            },
            {
              name: 'Staff Role',
              to: 'settings.roles.index',
            },
            {
              name: 'General',
              to: 'settings.general.index',
            },
        ]
      }
    ],
  }
  