--
-- Database: `fortify`
--

-- --------------------------------------------------------

--
-- Table structure for table `evidence`
--

CREATE TABLE `evidence` (
  `uid` varchar(64) NOT NULL,
  `filepath` text NOT NULL,
  `fortified` tinyint(1) NOT NULL,
  `caseindex` text NOT NULL,
  `uploaddate` bigint(20) NOT NULL,
  `lastmodified` bigint(20) NOT NULL,
  `nickname` text NOT NULL,
  `type` text NOT NULL,
  `user` text NOT NULL,
  `checksum` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `quickreport`
--

CREATE TABLE `quickreport` (
  `uid` varchar(64) NOT NULL,
  `nickname` text NOT NULL,
  `casenum` text NOT NULL,
  `location` text NOT NULL,
  `type` text NOT NULL,
  `tags` text NOT NULL,
  `evidence` text NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `officer` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `username` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `name` varchar(64) NOT NULL,
  `agency` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `password`, `name`, `agency`) VALUES
('dev', 'devpass', 'Developer', 'blueline_TN'),
('tyler', 'password', 'T. Wilbanks', 'blueline_TN'),
('zhwatts', 'password', 'Z. Watts', 'blueline_TN');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `evidence`
--
ALTER TABLE `evidence`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `uid` (`uid`),
  ADD KEY `uid_2` (`uid`);

--
-- Indexes for table `quickreport`
--
ALTER TABLE `quickreport`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `username_2` (`username`),
  ADD KEY `username` (`username`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
