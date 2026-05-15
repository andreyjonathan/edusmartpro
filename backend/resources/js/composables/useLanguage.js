import { ref, computed } from 'vue';

// Persisted global state (singleton)
const currentLang = ref(localStorage.getItem('app_language') || 'id');

const translations = {
  en: {
    // Navigation
    nav: {
      dashboard: 'Dashboard',
      students: 'Students',
      teachers: 'Teachers',
      employees: 'Employees',
      classrooms: 'Classes',
      subjects: 'Subjects',
      attendance: 'Attendance',
      studentAttendance: 'Student Attendance',
      teacherAttendance: 'Teacher Attendance',
      employeeAttendance: 'Employee Attendance',
      schedule: 'Schedule',
      payments: 'Payments',
      settings: 'Settings',
      schoolProfile: 'School Profile',
      academic: 'Academic',
      application: 'Application',
      signOut: 'Sign Out',
      users: 'Users',
      rolesPermissions: 'Roles & Permissions',
    },
    // Header
    header: {
      search: 'Search students, classes, records...',
      academicYear: 'Academic Year',
    },
    // Dashboard
    dashboard: {
      title: 'Dashboard',
      subtitle: 'Welcome back! Here\'s what\'s happening in your school today.',
      totalStudents: 'Total Students',
      totalTeachers: 'Total Teachers',
      totalEmployees: 'Total Employees',
      totalSubjects: 'Total Subjects',
      recentAttendance: 'Recent Attendance',
    },
    // Students
    students: {
      title: 'Student Management',
      subtitle: 'Manage and track all enrolled students.',
      addStudent: 'Add Student',
      editStudent: 'Edit Student',
      createStudent: 'Create New Student',
      search: 'Search Student',
      searchPlaceholder: 'Name or NIS...',
      filterClass: 'Class',
      filterStatus: 'Status',
      allClasses: 'All Classes',
      allStatus: 'All Status',
      name: 'Name',
      nis: 'NIS',
      class: 'Class',
      gender: 'Gender',
      status: 'Status',
      actions: 'Actions',
      noRecords: 'No students found.',
      backToList: 'Back to List',
    },
    // Teachers
    teachers: {
      title: 'Teacher Management',
      subtitle: 'Manage all teaching staff and their subjects.',
      addTeacher: 'Add Teacher',
      editTeacher: 'Edit Teacher',
      search: 'Search Teacher',
      searchPlaceholder: 'Name or NIP...',
      backToList: 'Back to List',
    },
    // Employees
    employees: {
      title: 'Employee Management',
      subtitle: 'Manage administrative and support staff.',
      addEmployee: 'Add Employee',
      editEmployee: 'Edit Employee',
      search: 'Search Employee',
      searchPlaceholder: 'Name or NIK...',
      backToList: 'Back to List',
    },
    // Attendance
    attendance: {
      studentTitle: 'Student Attendance',
      teacherTitle: 'Teacher Attendance',
      employeeTitle: 'Employee Attendance',
      studentSubtitle: 'Monitor and manage daily student presence and participation.',
      teacherSubtitle: 'Monitor and manage daily presence of teaching staff.',
      employeeSubtitle: 'Track and record attendance for administrative and support staff.',
      recordAttendance: 'Record Attendance',
      report: 'Report',
      search: 'Search Student',
      searchName: 'Search Name',
      searchPlaceholder: 'Name or NIS...',
      searchNamePlaceholder: 'Search by name...',
      date: 'Date',
      class: 'Class',
      status: 'Status',
      notes: 'Notes',
      allClasses: 'All Classes',
      allStatus: 'All Status',
      noRecords: 'No attendance records found.',
      backToList: 'Back to List',
      attendanceDetails: 'Attendance Details',
      statusRemarks: 'Status & Remarks',
      notesOptional: 'Notes (Optional)',
      notesPlaceholder: 'e.g. Arrived 15 mins late due to traffic',
      recordBtn: 'Record Attendance',
      updateBtn: 'Update Record',
      cancel: 'Cancel',
      editRecord: 'Edit Record',
      teacherRemarks: 'Remarks',
      noRemarks: 'No remarks provided for this session.',
    },
    // Subjects
    subjects: {
      title: 'Subject Management',
      subtitle: 'Manage all available school subjects.',
      addSubject: 'Add Subject',
      backToList: 'Back to List',
    },
    // Common
    common: {
      showing: 'Showing',
      to: 'to',
      of: 'of',
      records: 'records',
      active: 'Active',
      inactive: 'Inactive',
      male: 'Male',
      female: 'Female',
      present: 'Present',
      absent: 'Absent',
      late: 'Late',
      sick: 'Sick',
      save: 'Save',
      cancel: 'Cancel',
      delete: 'Delete',
      edit: 'Edit',
      view: 'View',
      student: 'Student',
      teacher: 'Teacher',
    },
    // Users
    users: {
      title: 'User Management',
      subtitle: 'Manage system users and their access levels.',
      rolesTitle: 'Roles & Permissions',
      rolesSubtitle: 'Define what different user roles can do in the system.',
      addUser: 'Add User',
      editUser: 'Edit User',
    },
  },

  id: {
    // Navigation
    nav: {
      dashboard: 'Dasbor',
      students: 'Siswa',
      teachers: 'Guru',
      employees: 'Karyawan',
      classrooms: 'Kelas',
      subjects: 'Mata Pelajaran',
      attendance: 'Kehadiran',
      studentAttendance: 'Absensi Siswa',
      teacherAttendance: 'Absensi Guru',
      employeeAttendance: 'Absensi Karyawan',
      schedule: 'Jadwal',
      payments: 'Pembayaran',
      settings: 'Pengaturan',
      schoolProfile: 'Profil Sekolah',
      academic: 'Akademik',
      application: 'Aplikasi',
      signOut: 'Keluar',
      users: 'Pengguna',
      rolesPermissions: 'Peran & Izin',
    },
    // Header
    header: {
      search: 'Cari siswa, kelas, data...',
      academicYear: 'Tahun Ajaran',
    },
    // Dashboard
    dashboard: {
      title: 'Dasbor',
      subtitle: 'Selamat datang kembali! Ini yang terjadi di sekolah Anda hari ini.',
      totalStudents: 'Total Siswa',
      totalTeachers: 'Total Guru',
      totalEmployees: 'Total Karyawan',
      totalSubjects: 'Total Mata Pelajaran',
      recentAttendance: 'Kehadiran Terbaru',
    },
    // Students
    students: {
      title: 'Manajemen Siswa',
      subtitle: 'Kelola dan pantau semua siswa yang terdaftar.',
      addStudent: 'Tambah Siswa',
      editStudent: 'Edit Siswa',
      createStudent: 'Buat Siswa Baru',
      search: 'Cari Siswa',
      searchPlaceholder: 'Nama atau NIS...',
      filterClass: 'Kelas',
      filterStatus: 'Status',
      allClasses: 'Semua Kelas',
      allStatus: 'Semua Status',
      name: 'Nama',
      nis: 'NIS',
      class: 'Kelas',
      gender: 'Jenis Kelamin',
      status: 'Status',
      actions: 'Aksi',
      noRecords: 'Tidak ada siswa yang ditemukan.',
      backToList: 'Kembali ke Daftar',
    },
    // Teachers
    teachers: {
      title: 'Manajemen Guru',
      subtitle: 'Kelola semua staf pengajar dan mata pelajaran mereka.',
      addTeacher: 'Tambah Guru',
      editTeacher: 'Edit Guru',
      search: 'Cari Guru',
      searchPlaceholder: 'Nama atau NIP...',
      backToList: 'Kembali ke Daftar',
    },
    // Employees
    employees: {
      title: 'Manajemen Karyawan',
      subtitle: 'Kelola staf administrasi dan pendukung.',
      addEmployee: 'Tambah Karyawan',
      editEmployee: 'Edit Karyawan',
      search: 'Cari Karyawan',
      searchPlaceholder: 'Nama atau NIK...',
      backToList: 'Kembali ke Daftar',
    },
    // Attendance
    attendance: {
      studentTitle: 'Absensi Siswa',
      teacherTitle: 'Absensi Guru',
      employeeTitle: 'Absensi Karyawan',
      studentSubtitle: 'Pantau dan kelola kehadiran siswa setiap hari.',
      teacherSubtitle: 'Pantau dan kelola kehadiran staf pengajar setiap hari.',
      employeeSubtitle: 'Lacak dan catat kehadiran staf administrasi dan pendukung.',
      recordAttendance: 'Catat Kehadiran',
      report: 'Laporan',
      search: 'Cari Siswa',
      searchName: 'Cari Nama',
      searchPlaceholder: 'Nama atau NIS...',
      searchNamePlaceholder: 'Cari berdasarkan nama...',
      date: 'Tanggal',
      class: 'Kelas',
      status: 'Status',
      notes: 'Catatan',
      allClasses: 'Semua Kelas',
      allStatus: 'Semua Status',
      noRecords: 'Tidak ada data kehadiran yang ditemukan.',
      backToList: 'Kembali ke Daftar',
      attendanceDetails: 'Detail Kehadiran',
      statusRemarks: 'Status & Keterangan',
      notesOptional: 'Catatan (Opsional)',
      notesPlaceholder: 'mis. Terlambat 15 menit karena macet',
      recordBtn: 'Catat Kehadiran',
      updateBtn: 'Perbarui Data',
      cancel: 'Batal',
      editRecord: 'Edit Data',
      teacherRemarks: 'Keterangan',
      noRemarks: 'Tidak ada keterangan untuk sesi ini.',
    },
    // Subjects
    subjects: {
      title: 'Manajemen Mata Pelajaran',
      subtitle: 'Kelola semua mata pelajaran yang tersedia.',
      addSubject: 'Tambah Mata Pelajaran',
      backToList: 'Kembali ke Daftar',
    },
    // Common
    common: {
      showing: 'Menampilkan',
      to: 'sampai',
      of: 'dari',
      records: 'data',
      active: 'Aktif',
      inactive: 'Tidak Aktif',
      male: 'Laki-laki',
      female: 'Perempuan',
      present: 'Hadir',
      absent: 'Tidak Hadir',
      late: 'Terlambat',
      sick: 'Sakit',
      save: 'Simpan',
      cancel: 'Batal',
      delete: 'Hapus',
      edit: 'Edit',
      view: 'Lihat',
      student: 'Siswa',
      teacher: 'Guru',
      employee: 'Karyawan',
    },
    // Users
    users: {
      title: 'Manajemen Pengguna',
      subtitle: 'Kelola pengguna sistem dan tingkat akses mereka.',
      rolesTitle: 'Peran & Izin',
      rolesSubtitle: 'Tentukan apa yang dapat dilakukan oleh berbagai peran pengguna dalam sistem.',
      addUser: 'Tambah Pengguna',
      editUser: 'Edit Pengguna',
    },
  },
};

export function useLanguage() {
  const toggleLanguage = () => {
    currentLang.value = currentLang.value === 'en' ? 'id' : 'en';
    localStorage.setItem('app_language', currentLang.value);
  };

  const setLanguage = (lang) => {
    if (translations[lang]) {
      currentLang.value = lang;
      localStorage.setItem('app_language', lang);
    }
  };

  const t = computed(() => translations[currentLang.value]);

  const isEnglish = computed(() => currentLang.value === 'en');
  const isIndonesian = computed(() => currentLang.value === 'id');

  return {
    lang: currentLang,
    t,
    isEnglish,
    isIndonesian,
    toggleLanguage,
    setLanguage,
  };
}
