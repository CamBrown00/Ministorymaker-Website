# Parse text into sentences.
# Parses a text file containing only the story sentences.
# (No title/author info yet)
#
# 4/2019 AriaRay Brown
#

# Open the text file 
infilename = input("Enter text file name (without .txt): ") + ".txt"
infile = open(infilename, 'r') 

# Create a new output text file
outfilename = infilename.replace(".txt", "Sents.txt")
outfile = open(outfilename, 'w')

story = infile.readlines()

# Split story into list of sentences separated by ". ", then put back the ". "
d = ". "
sentences =  [e+d for e in story[0].split(d) if e]

print(sentences)

# Need to split for other char endings... 

# Write sentences line by line to file
for s in sentences:
    outfile.write(s + "\n")

print("\n" + outfilename + " has been written.")

# Close files
infile.close()
outfile.close()

