-- Membuat database
drop database if exists ReservasiHotelDB;
CREATE DATABASE IF NOT EXISTS ReservasiHotelDB;
USE ReservasiHotelDB;

-- Membuat tabel Tamu
ENGINE=InnoDb;
CREATE TABLE Tamu (
    no_telepon VARCHAR(15) NOT NULL,
    nama VARCHAR(30) NOT NULL,
    alamat TEXT NOT NULL, 
    email VARCHAR(30) NOT NULL UNIQUE,
    loyalitas Varchar(40) NOT NULL DEFAULT "Brnoze",
    total_pengeluaran INT (11),
    primary key (no_telepon)
)
ENGINE=InnoDb;

-- Membuat tabel Kamar
CREATE TABLE Kamar (
    no_kamar VARCHAR(3) NOT NULL ,
    status_kamar varchar(9) NOT NULL DEFAULT 'Tersedia',
    tipe_kamar VARCHAR(20) NOT NULL,
    harga_per_malam INT(8) NOT NULL,
    primary key (no_kamar)
)
ENGINE=InnoDb;

-- Membuat tabel Reservasi
CREATE TABLE Reservasi (
    id_reservasi VARCHAR(4) NOT NULL ,
    no_telepon VARCHAR(15),
    no_kamar VARCHAR(3),
    tanggal_check_in DATE NOT NULL,
    tanggal_check_out DATE NOT NULL,
    status_reservasi varchar(9) NOT NULL DEFAULT 'Belum Lunas',
    primary key  (id_reservasi),
    FOREIGN KEY (no_telepon) REFERENCES Tamu(no_telepon),
    FOREIGN KEY (no_kamar) REFERENCES Kamar(no_kamar)
)
ENGINE=InnoDb;

CREATE TABLE Pembayaran (
    id_pembayaran int AUTO_INCREMENT ,
    id_reservasi VARCHAR(4) NOT NULL ,
    no_telepon  VARCHAR(15) NOT NULL ,
    tanggal_pembayaran DATE NOT NULL ,
    total_pembayaran INT(9) NOT NULL ,
    primary key (id_pembayaran),
    FOREIGN KEY (id_reservasi) REFERENCES Reservasi(id_reservasi),
    FOREIGN KEY (no_telepon) REFERENCES Tamu(no_telepon)
)
ENGINE=InnoDb;

CREATE TABLE log_reservasi (
    id_log INT AUTO_INCREMENT PRIMARY KEY,
    id_pembayaran INT,
    total_pembayaran DECIMAL(10, 2),
    tanggal_dihapus DATETIME,
    id_reservasi VARCHAR(4)
)
ENGINE=InnoDb;

-- Memasukkan data ke tabel Tamu
INSERT INTO Tamu (no_telepon, nama, email, alamat) VALUES
('01222333344444', 'Hwang Ye-ji', 'yeji@example.com', 'Seoul 23-1, Mapo-gu, Sangsu-dong'),
('08119876543350', 'Shin Yu-na', 'yuna@example.com', 'Wausan-ro 5-an-gil, Mapo-gu, Seoul'),
('08130987654340', 'Choi Ji-su', 'jisu@example.com', 'Gyeongsang , Ansan'),
('01029387648370', 'Shin Ryu-jin', 'ryujin@example.com', 'Gyeonggi, Java,Anseong'),
('01209837463890', 'Lee Ji-eun', 'iu@example.com', 'Gongju, Chungcheong'),
('01029387468374', 'Wonhee', 'wonhee@example.com', 'Gumi, Gyeongsang Utara'),
('01010102292998', 'Minju', 'minju@example.com', 'Iksan, Jeolla Utara'),
('07778998837465', 'Iroha', 'iroha@example.com', 'Jeju, Jeju'),
('08765892838645', 'Moka', 'moka@example.com', 'Mokpo, Jeolla'),
('00128889990001', 'Yunah', 'yunah@example.com', 'Seorin-dong Namjeju-gun JEJU-DO, SEOUL'),
('8217981624684', 'Mikael Gabruella', 'Mikagab@example.com', '321 Main St, New York'),
('74538473760178', 'Bob johnson', 'bobjon@example.com', '203 Elm St, Los Angeles'),
('78372837648200', 'Mikasa Yeager', 'mikayeag@example.com', '212 Himawari St , Hokaido'),
('00019283654838', 'David Hartono', 'hatodav@example.com', '333 Ngawi, Java'),
('87476384777649', 'Yamada Akasa', 'akai@example.com', '654 X District, Tokyo'),
('09967763846527', 'Franko Jawa', 'frankjawa@example.com', '444 Ngawi, Java '),
('65364528226638', 'Elaine Brue', 'elainb@example.com', '123 Spruce St, San Diego'),
('00098079767582', 'Ukitora Himawari', 'himawari@example.com', '456 Birch St, San Diego'),
('09087736552836', 'Monkey D Luffy', 'luffym@example.com', '789 Redwood St, Dallas'),
('09070638528253', 'Juliana Gege', 'julianag@example.com', '321 Aspen St, San Jose'),
('081234567890', 'Andi Wijaya', 'andi@gmail.com', 'Jalan Merdeka No. 1'),
('081234567891', 'Budi Setiawan', 'budi@gmail.com', 'Jalan Sudirman No. 2'),
('081234567892', 'Citra Dewi', 'citra@gmail.com', 'Jalan Gatot Subroto No. 3'),
('081234567893', 'Dewi Pertiwi', 'dewi@gmail.com', 'Jalan M.H. Thamrin No. 4'),
('081234567894', 'Eka Saputra', 'eka@gmail.com', 'Jalan Kuningan No. 5'),
('081234567895', 'Fajar Santoso', 'fajar@gmail.com', 'Jalan Senopati No. 6'),
('081234567896', 'Gina Wijaya', 'gina@gmail.com', 'Jalan Tebet Raya No. 7'),
('081234567897', 'Hadi Prasetyo', 'hadi@gmail.com', 'Jalan Diponegoro No. 8'),
('081234567898', 'Indah Lestari', 'indah@gmail.com', 'Jalan Fatmawati No. 9'),
('081234567899', 'Joko Susanto', 'joko@gmail.com', 'Jalan Panglima Polim No. 10'),
('1234567890', 'Alice Johnson', 'alice@example.com', '123 Main St, New York, NY'),
('0987654321', 'Bob Smith', 'bob@example.com', '456 Elm St, Los Angeles, CA'),
('1112223334', 'Carol White', 'carol@example.com', '789 Oak St, Chicago, IL'),
('2223334445', 'David Brown', 'david@example.com', '321 Pine St, Houston, TX'),
('3334445556', 'Emma Green', 'emma@example.com', '654 Maple St, Phoenix, AZ'),
('4445556667', 'Frank Black', 'frank@example.com', '987 Cedar St, Philadelphia, PA'),
('5556667778', 'Grace Blue', 'grace@example.com', '123 Spruce St, San Antonio, TX'),
('6667778889', 'Hannah Yellow', 'hannah@example.com', '456 Birch St, San Diego, CA'),
('7778889990', 'Ian Red', 'ian@example.com', '789 Redwood St, Dallas, TX'),
('8889990001', 'Julia Purple', 'julia@example.com', '321 Aspen St, San Jose, CA');

-- Memasukkan data ke tabel Kamar
INSERT INTO Kamar (no_kamar, tipe_kamar, harga_per_malam) VALUES
-- status kamar tidak dimasukan karna default nya tersedia

('101', 'Standar', 350000),
('102', 'Deluxe', 500000),
('103', 'Suite', 750000),
('104', 'Suite', 750000),
('105', 'Standar', 350000),
('106', 'Deluxe', 500000),
('107', 'Suite', 750000),
('108', 'Standar', 350000),
('109', 'Deluxe', 500000),
('110', 'Suite', 750000),
('111', 'Single', 500000 ), 
('112', 'Single', 500000 ), 
('113', 'Double', 800000 ), 
('114', 'Double', 800000 ), 
('115', 'Suite', 2500000 ), 
('116', 'Suite', 2500000 ), 
('117', 'Single', 500000 ), 
('118', 'Double', 800000 ), 
('119', 'Suite', 2500000 ), 
('120', 'Double', 800000 ),
('201', 'Single', 500000 ), 
('202', 'Single', 500000 ), 
('203', 'Double', 800000 ), 
('204', 'Double', 800000 ), 
('205', 'Suite', 2500000 ), 
('206', 'Suite', 2500000 ), 
('207', 'Single', 500000 ), 
('208', 'Double', 800000 ), 
('209', 'Suite', 2500000 ), 
('210', 'Double', 800000 ),
('211', 'Single', 500000),
('212', 'Single', 500000),
('213', 'Double', 800000),
('214', 'Double', 800000),
('215', 'Suite', 1500000),
('216', 'Suite', 1500000),
('217', 'Single', 500000),
('218', 'Double', 800000),
('219', 'Suite', 1500000),
('220', 'Double', 800000);

-- Memasukkan data ke tabel Reservasi
INSERT INTO Reservasi (id_reservasi, no_telepon, no_kamar, tanggal_check_in, tanggal_check_out) VALUES
('R001', '081234567890','101', '2024-10-01', '2024-10-05'),
('R002', '081234567891','102', '2024-10-03', '2024-10-06'),
('R003', '081234567892','103', '2024-10-04', '2024-10-07'),
('R004', '081234567893','104', '2024-10-05', '2024-10-08'),
('R005', '081234567894','105', '2024-10-06', '2024-10-09'),
('R006', '081234567895','106', '2024-10-07', '2024-10-10'),
('R007', '081234567896','107', '2024-10-08', '2024-10-11'),
('R008', '081234567897','108', '2024-10-09', '2024-10-12'),
('R009', '081234567898','109', '2024-10-10', '2024-10-13'),
('R010', '081234567899','110', '2024-10-11', '2024-10-14'),
('R011', '081234567890','111', '2024-01-01', '2024-01-02'),
('R012', '74538473760178','112', '2024-01-03', '2024-01-04'),
('R013', '78372837648200','113', '2024-01-05', '2024-01-06'),
('R014', '081234567890','114', '2024-01-08', '2024-01-09'),
('R015', '87476384777649','115', '2024-01-01', '2024-01-03'),
('R016', '09967763846527','116', '2024-01-02', '2024-01-03'),
('R017', '65364528226638','117', '2024-01-03', '2024-01-05'),
('R018', '00098079767582','118', '2024-01-10', '2024-01-13'),
('R019', '09087736552836','119', '2024-01-12', '2024-01-13'),
('R020', '09070638528253','120', '2024-01-14', '2024-01-16'),
('R021', '01222333344444','201', '2024-01-01', '2024-01-02'),
('R022', '081234567893','202', '2024-01-03', '2024-01-04'),
('R023', '08130987654340','203', '2024-01-05', '2024-01-06'),
('R024', '01029387648370','203', '2024-01-08', '2024-01-09'),
('R025', '081234567896','205', '2024-01-01', '2024-01-03'),
('R026', '081234567893','206', '2024-01-02', '2024-01-03'),
('R027', '01010102292998','207', '2024-01-03', '2024-01-05'),
('R028', '07778998837465','208', '2024-01-10', '2024-01-13'),
('R029', '08765892838645','209', '2024-01-12', '2024-01-13'),
('R030', '00128889990001','210', '2024-01-14', '2024-01-16'),
('R031', '1234567890','211', '2024-10-10', '2024-10-12'),
('R032', '0987654321','212', '2024-10-11', '2024-10-13'),
('R033', '1112223334','213', '2024-10-15', '2024-10-17'),
('R034', '2223334445','214', '2024-10-18', '2024-10-20'),
('R035', '081234567893','215', '2024-10-19', '2024-10-21'),
('R036', '081234567896','216', '2024-10-20', '2024-10-22'),
('R037', '5556667778','217', '2024-10-23', '2024-10-25'),
('R038', '6667778889','218', '2024-10-24', '2024-10-26'),
('R039', '7778889990','219', '2024-10-25', '2024-10-27'),
('R040', '8889990001','220', '2024-10-28', '2024-10-30');

--Memasukan data ke tabel Pembayaran
INSERT INTO Pembayaran (id_reservasi, no_telepon, tanggal_pembayaran, total_pembayaran) VALUES
('R001','081234567890','2024-10-05', 1400000),
('R002','081234567891','2024-10-06', 1500000),
('R003','081234567892','2024-10-07', 2250000),
('R004','081234567893','2024-10-08', 2250000),
('R005','081234567894','2024-10-09', 1400000),
('R006','081234567895','2024-10-10', 1500000),
('R007','081234567896','2024-10-11', 2250000),
('R008','081234567897','2024-10-12', 1400000),
('R009','081234567898','2024-10-13', 1500000),
('R010','081234567899','2024-10-14', 2250000),
('R011','081234567890','2023-12-30', 500000 ),
('R012','74538473760178','2024-01-01', 500000 ),
('R013','78372837648200','2024-01-02', 800000 ),
('R014','081234567890','2024-01-03', 800000 ),
('R015','87476384777649','2024-01-01',2500000 ),
('R016','09967763846527','2023-12-25',2500000 ),
('R017','65364528226638','2024-01-01', 500000 ),
('R018','00098079767582','2024-01-05', 800000 ),
('R019','09087736552836','2024-01-09',2500000 ),
('R020','09070638528253','2024-01-10', 800000 ),
('R021','01222333344444','2023-12-30', 500000 ),
('R022','081234567893','2024-01-01', 500000 ),
('R023','08130987654340','2024-01-02', 800000 ),
('R024','01029387648370','2024-01-03', 800000 ),
('R025','081234567896','2024-01-01',2500000 ),
('R026','081234567893','2023-12-25',2500000 ),
('R027','01010102292998','2024-01-01', 500000 ),
('R028','07778998837465','2024-01-05', 800000 ),
('R029','08765892838645','2024-01-09',2500000 ),
('R030','00128889990001','2024-01-10', 800000 ),
('R031','1234567890','2024-10-12', 1000000),
('R032','0987654321','2024-10-13', 1600000),
('R033','1112223334','2024-10-17', 3000000),
('R034','2223334445','2024-10-20', 1000000),
('R035','081234567893','2024-10-21', 1600000),
('R036','081234567896','2024-10-22', 3000000),
('R037','5556667778','2024-10-25', 1600000),
('R038','6667778889','2024-10-26', 3000000),
('R039','7778889990','2024-10-27', 1600000),
('R040','8889990001','2024-10-30', 1000000);