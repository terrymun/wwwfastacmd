# Help for fastacmd

## Introduction
fastacmd retrives FASTA formatted sequences from a blast database, as long as
it it was successfully formatted using the `-o` option.  

## Command line options
The arguments available in fastacmd 2.2.10 :

 * `-d` &mdash; Database
   * Type: String
   * Required: No (optional)
   * Default: "nr"
   
 * `-p` &mdash; Type of file
   * Type: String
   * Required: No (optional)
   * Values:
     * `G` &mdash; default option, guess mode (look for protein, then nucleotide)
     * `T` &mdash; protein
     * `F` &mdash; nucleotide
     
 * `-s` &mdash; Search string. GIs, accessions and loci may be used, delimited by comma
   * Type: String
   * Required: No (optional)
   
 * `-i` &mdash; Input file wiht GIs/accessions/loci for batch retrieval
   * Type: String
   * Required: No (optional)
   
 * `-a` &mdash; Retrieve duplicate accessions
    * Type: Boolean (T/F)
    * Required: No (optional)
    * Default: 'F'
    
 * `-l` &mdash; Line length for sequence
   * Type: Integer
   * Required: No (optional)
   * Default: 80
   
 * `-t` &mdash; Definition line should contain target gi only
   * Type: Boolean (T/F)
   * Required: No (optional)
   * Default: 'F'
   
 * `-o` &mdash; Output file
   * Type: String (file path)
   * Required: No (optional)
   * Default: "stdout"
   
 * `-c` &mdash; Use Ctrl-A as non-redundant defline separator
   * Type: Boolean (T/F)
   * Required: No (optional)
   * Default: "F"
   
 * `-D` &mdash; Dump the entire database as FASTA or Gi list
   * Type: Integer
   * Required: No (optional)
   * Default: 0 (do not dump anything)
   * Values:
     * `1` &mdash; FASTA
     * `2` &mdash; Gi list
     
 * `-L` &mdash; Range of sequence to extract
   * Type: String
   * Required: No (optional)
   * Format: (start, stop)
   * Default: 0,0
   * Notes:
     * `0` in start refers to the beginning of the sequence
     * `0` in end refers to the end of the sequence

 * `-S` &mdash; Strand on subsequence (nucloetide only):
   * Type: Integer
   * Default: 1
   * Values:
     * `1` &mdash; top
     * `2` &mdash; bottom
     
 * `-T` &mdash; Print taxonomic information for requested sequence(s)
   * Type: Boolean (T/F)
   * Default: F
   
 * `-I` &mdash; Print database information only (overrides all other options)
   * Type: Boolean (T/F)
   * Default: F
   
 * `-P` &mdash; Retrieve sequences with this PIG
   * Type: Integer
   * Required: No (optional)
 
 
## Usage
 
###To retrieve a sequence by identifier:
 
    fastacmd -d nt -s 555
    >gi|555|emb|X65215.1|BTMISATN B.taurus microsatellite DNA (624bp)
    ACCTCCACTAGCTTTGTTTGTAGTGATGCTCTGTAGCACCACTGGGAAGCCCTTTAATGAATGTGCCTTTCCGCAAATCA
    CACACACACAAATACACTTATAGAAACAAGGTGATTTTCTTGAAATAATAAAACAAAATTTGGAAGAAGATTTTTACTGT
    CTTAGGAAAAGTAAGGCATTGGAAGGTGGCTAGGTATGACATATGAAGTTGCATTTTAAAACTGGAATTGGACAACTGAT
    ATTCAGTGATATTTATGCTACTACCTTCTAGAATCGAGAGCATGCACCCCACTCTGTACTCTTGCCTGGAGAATCCATGA
    TGAGAGCCTGGTAGGCTGCAGTCCATGGGGTCACACAGAGTCGGACATGACTGAGCGACTTCACTTTCACTTTTCAATTT
    CATGCATTGGAGCCGGAAATGGCAACCCACTCCAGTGTTCTTGCCTGGAGAATCCCAGGGATGGGGAAGCCTGGTGGGCT
    GCTGTCTATGGGGTCGCAGAGAGTCAGACACGACTGAAGTGACTTAGCAGCAACCTTCTGGAATAAACGCCTCAGGCTTT
    AAACTCTGGCTTGACCATTCACTAGCCATGGGATCCACTAGAGTCGACCTGCAGGCATGCAAGC
 
If the identifier is not a gi or an accession, you must pass the entire seqid
string to fastacmd. That is, if your sequence is
 
    >gnl|mydb|myid my sequence description
    ACGT..
 
... then you must search for it with `fastacmd -d mydb -s 'gnl|mydb|myid'`
 
### To obtain a FASTA file from a blast database:
 
`fastacmd -D 1 -d nt -o nt.fsa`
 
### To retrieve only part of a sequence:
 
    fastacmd -d nt -s 555 -L0,32
    gi|555:1-32 B.taurus microsatellite DNA (624bp)
    ACCTCCACTAGCTTTGTTTGTAGTGATGCTCT
 
The above command will return one of the following exit values:
 - `0` &mdash; Completed successfully
 - `1` &mdash; An error occured
 - `2` &mdash; Blast database was not found
 - `3` &mdash; Failed search (accession, gi, taxonomy info)
 - `4` &mdash; No taxonomy database was found
 

