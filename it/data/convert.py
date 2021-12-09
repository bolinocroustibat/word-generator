import os

INFILEPATH = "Italian.dic.txt"
OUTFILE_PATH = "dictionary_IT.txt"

with open(OUTFILE_PATH, 'w', encoding='utf-8') as outfile:
    with open(INFILEPATH, 'r', encoding='utf-8') as infile:
        count = 0
        for line in infile.readlines():
            words = line.split("/")
            try:
                outfile.write(f"{words[0]}\n")
            except:
                pass
            count += 1

with open(OUTFILE_PATH, 'r+') as fd:
    lines = fd.readlines()
    fd.seek(0)
    fd.writelines(line for line in lines if line.strip())
    fd.truncate()

print(f"{count} words written.")
